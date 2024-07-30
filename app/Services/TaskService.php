<?php

namespace App\Services;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Log;
use Aws\Exception\AwsException;

class TaskService
{
    protected $taskRepository;
    protected $fileService;

    public function __construct(TaskRepository $taskRepository, FileService $fileService)
    {
        $this->taskRepository = $taskRepository;
        $this->fileService = $fileService;
    }

    public function all(string $user_id)
    {
        return $this->taskRepository->all($user_id);
    }

    public function show($id)
    {
        return $this->taskRepository->show($id);
    }

    public function create(StoreTaskRequest $request)
    {
        try {
            $user = auth()->guard('sanctum')->user();
            $user_id = $user->id;

            $data = $request->validated();
            $files = $request->file('files');

            $task = $this->taskRepository->create([
                'user_id' => $user_id,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'status' => $data['status'],
            ]);

            if (!is_null($files) && count($files) > 0) {
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        try {
                            $task_id = $task->id;
                            $file_name = $file->getClientOriginalName();
                            $keyname = "{$user_id}/{$task_id}";
                            $path = $this->fileService->upload($keyname, $file_name, $file);

                            $this->fileService->create([
                                'task_id' => $task->id,
                                'path' => $path
                            ]);
                        } catch (\Throwable $th) {
                            Log::error("[ERROR] TaskService.create (foreach) - " . $th);
                            return response()->json(['message' => 'Internal server error'], 500);
                        } catch (AwsException $e) {
                            Log::error("[ERROR] TaskService.FileService.upload: " . $e->getMessage());
                            throw new \Error("Failed to upload file" . $e->getMessage());
                        }
                    } else {
                        Log::warning("[WARNING] Invalid file encountered: " . json_encode($file));
                    }
                }
            } else {
                Log::info("[INFO] No files to process.");
            }

            $task->load('files');
            return $task;
        } catch (\Throwable $th) {
            Log::error("[ERROR] TaskService.create - " . $th);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function update(UpdateTaskRequest $request, $task_id)
    {
        try {
            // All except files
            $data = $request->all();
            $files = $request->file('files');

            $task = $this->taskRepository->show($task_id);

            if (!is_null($task->files) && count($task->files) > 0) {
                foreach ($task->files as $file) {

                    $split_path = explode('/', $file->path);
                    $file_name = end($split_path);
                    $file_deteled = $this->fileService->delete_file($file->path, $file_name);
                    Log::debug("[DEBUG - Delete] TaskService.update - file_deleted: " . $file_deteled);

                    $file->delete();
                }
            } else {
                Log::info("[INFO] No files to process.");
            }

            if (!is_null($files) && count($files) > 0) {
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        try {
                            $file_name = $file->getClientOriginalName();
                            $keyname = "{$task->user_id}/{$task_id}";

                            $path = $this->fileService->upload($keyname, $file_name, $file);
                            $this->fileService->create([
                                'task_id' => $task_id,
                                'path' => $path,
                            ]);
                        } catch (\Throwable $th) {
                            Log::error("[ERROR] TaskService.update (foreach) - " . $th);
                            return response()->json(['message' => 'Internal server error'], 500);
                        } catch (\Throwable $th) {
                            Log::error("[ERROR] TaskService.update (upload) - " . $th);
                            return response()->json(['message' => 'Internal server error'], 500);
                        }
                    } else {
                        Log::warning("[WARNING] Invalid file encountered: " . json_encode($file));
                    }
                }
            }

            $task->load('files');
            $this->taskRepository->update([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'status' => $data['status'],
            ], $task_id);

            return $task;
        } catch (\Throwable $th) {
            Log::error("[ERROR] TaskService.update - " . $th);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function destroy($id)
    {
        return $this->taskRepository->destroy($id);
    }
}
