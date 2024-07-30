<?php

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Log;
use Aws\Exception\AwsException;

use \Illuminate\Http\UploadedFile;

class FileService
{
    protected $fileRepository;
    protected $s3Client;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->s3Client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function upload(string $keyname, string $file_name, UploadedFile $file): string
    {
        return $file->storeAs($keyname, $file_name, 's3');
    }

    public function download(string $keyname): string
    {
        try {
            $result = $this->s3Client->getObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $keyname,
            ]);

            return base64_encode($result['Body']);
        } catch (AwsException $e) {
            Log::error("[ERROR] FileService.download: " . $e->getMessage());
            throw new \Error("Failed to download file: " . $e->getMessage());
        }
    }

    public function delete_file(string $keyname, string $file_name)
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $keyname,
            ]);

            return true;
        } catch (AwsException $e) {
            Log::error("[ERROR] FileService.deleteFile: " . $e->getMessage());
            return false;
        }
    }

    public function create(array $data)
    {
        return $this->fileRepository->create($data);
    }

    public function delete(string $id)
    {
        return $this->fileRepository->delete($id);
    }

    public function find(string $id)
    {
        return $this->fileRepository->find($id);
    }

    public function findByTaskId(string $task_id)
    {
        return $this->fileRepository->findByTaskId($task_id);
    }

    public function update(string $id, array $data)
    {
        return $this->fileRepository->update($id, $data);
    }

    public function deleteByTaskId(string $task_id)
    {
        return $this->fileRepository->deleteByTaskId($task_id);
    }
}
