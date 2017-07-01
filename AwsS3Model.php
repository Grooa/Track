<?php

namespace Plugin\Track;

use Aws\S3\S3Client;
use Ip\Exception;

class AwsS3Model
{

    private static $s3;
    private static $bucket;
    private static $expiration;

    public static function init()
    {
        AwsS3Model::$bucket = ipGetOption('Track.awsS3Bucket');
        AwsS3Model::$expiration = ipGetOption('Track.awsUrlExpiration');
        AwsS3Model::$s3 = new S3Client([
            'region' => ipGetOption('Track.region'),
            'version' => ipGetOption('Track.version'),
            'credentials' => [
                'key' => ipGetOption('Track.awsKey'),
                'secret' => ipGetOption('Track.awsSecret')
            ]
        ]);
    }

    public static function getPresignedUri($item, $config = [])
    {
        if (empty(AwsS3Model::$bucket) && empty($config['bucket'])) {
            throw new Exception("Missing default Aws S3 `bucket` in Plugin configuration");
        }

        if (empty(AwsS3Model::$expiration) && empty($config['expiration'])) {
            throw new Exception("Missing default `expiration` time for Presigned Uris, for Aws S3");
        }

        $cmd = AwsS3Model::$s3->getCommand('GetObject', [
            'Bucket' => !empty($config['bucket']) ? $config['bucket'] : AwsS3Model::$bucket,
            'Key' => $item
        ]);

        $request = AwsS3Model::$s3->createPresignedRequest(
            $cmd,
            !empty($config['expiration']) ? $config['expiration'] : AwsS3Model::$expiration
        );

        return (string)$request->getUri();
    }
}

AwsS3Model::init(); // Workaround to init static fields