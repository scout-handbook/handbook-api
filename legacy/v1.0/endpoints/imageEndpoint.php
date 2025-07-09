<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Exception\FileUploadException;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;
use Skautis\Skautis;

$imageEndpoint = new Endpoint;

function applyRotation(Imagick $image): void
{
    switch ($image->getImageOrientation()) {
        case Imagick::ORIENTATION_TOPRIGHT:
            $image->flopImage();
            break;
        case Imagick::ORIENTATION_BOTTOMRIGHT:
            $image->rotateImage('#000', 180);
            break;
        case Imagick::ORIENTATION_BOTTOMLEFT:
            $image->flopImage();
            $image->rotateImage('#000', 180);
            break;
        case Imagick::ORIENTATION_LEFTTOP:
            $image->flopImage();
            $image->rotateImage('#000', -90);
            break;
        case Imagick::ORIENTATION_RIGHTTOP:
            $image->rotateImage('#000', 90);
            break;
        case Imagick::ORIENTATION_RIGHTBOTTOM:
            $image->flopImage();
            $image->rotateImage('#000', 90);
            break;
        case Imagick::ORIENTATION_LEFTBOTTOM:
            $image->rotateImage('#000', -90);
            break;
        default:
            break;
    }
    $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
}

$listImages = function (): array {
    $SQL = <<<'SQL'
SELECT `id`
FROM `images`
ORDER BY `time` DESC;
SQL;

    $db = new Database;
    $db->prepare($SQL);
    $db->execute();
    $id = '';
    $db->bindColumn('id', $id);
    $images = [];
    while ($db->fetch()) {
        $images[] = Uuid::fromBytes(strval($id))->toString();
    }

    return ['status' => 200, 'response' => $images];
};
$imageEndpoint->setListMethod(new Role('editor'), $listImages);

$getImage = function (Skautis $skautis, array $data) use ($CONFIG): array {
    $id = Helper::parseUuid($data['id'], 'image')->toString();
    $quality = 'web';
    if (isset($data['quality']) and in_array($data['quality'], ['original', 'web', 'thumbnail'])) {
        $quality = $data['quality'];
    }

    $file = $CONFIG->imagepath.'/'.$quality.'/'.$id.'.jpg';

    if (! file_exists($file)) {
        throw new NotFoundException('image');
    }

    header('content-type: '.mime_content_type($file));
    // TODO: Causes errors in Firefox, find out why: Maybe some data is sent before the image?
    // header('content-length: ' . filesize($file));

    $modified = filemtime($file);
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $ifMod = new DateTime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
        if ($ifMod->format('U') > $modified) {
            http_response_code(304);

            return ['status' => 304];
        }
    }

    if ($modified) {
        header('last-modified: '.date('r', $modified));
    }
    readfile($file);

    return ['status' => 200];
};
$imageEndpoint->setGetMethod(new Role('guest'), $getImage);

$addImage = function () use ($CONFIG): array {
    $SQL = <<<'SQL'
INSERT INTO `images` (`id`)
VALUES (:id);
SQL;

    if (! isset($_FILES['image'])) {
        throw new MissingArgumentException(MissingArgumentException::FILE, 'image');
    }
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new FileUploadException($_FILES['image']['error']);
    }
    if (! getimagesize($_FILES['image']['tmp_name'])) {
        throw new InvalidArgumentTypeException('image', ['image/jpeg', 'image/png']);
    }
    $extension = mb_strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (! in_array($extension, ['jpg', 'jpeg', 'png'])) {
        throw new InvalidArgumentTypeException('image', ['image/jpeg', 'image/png']);
    }
    $uuid = Uuid::uuid4();
    $tmp = $CONFIG->imagepath.'/tmp/'.$uuid->toString().'.'.$extension;
    if (! move_uploaded_file($_FILES['image']['tmp_name'], $tmp)) {
        throw new \Skaut\HandbookAPI\v1_0\Exception\Exception('File upload failed.');
    }

    $db = new Database;
    $db->beginTransaction();
    $db->prepare($SQL);
    $uuidBin = $uuid->getBytes();
    $db->bindParam(':id', $uuidBin, PDO::PARAM_STR);
    $db->execute();

    $orig = $CONFIG->imagepath.'/original/'.$uuid->toString().'.jpg';
    $web = $CONFIG->imagepath.'/web/'.$uuid->toString().'.jpg';
    $thumbnail = $CONFIG->imagepath.'/thumbnail/'.$uuid->toString().'.jpg';

    $tmpMagick = new Imagick($tmp);
    $origMagick = new Imagick;
    $origMagick->newImage($tmpMagick->getImageWidth(), $tmpMagick->getImageHeight(), new ImagickPixel('white'));
    $origMagick->compositeImage($tmpMagick, imagick::COMPOSITE_OVER, 0, 0);
    $ICCProfile = $origMagick->getImageProfiles('icc', true);
    applyRotation($origMagick);
    $origMagick->stripImage();
    if (! empty($ICCProfile)) {
        $origMagick->profileImage('icc', $ICCProfile['icc']);
    }
    $origMagick->setFormat('JPEG');
    $origMagick->writeImage($orig);
    chmod($orig, 0444);
    unlink($tmp);

    $webMagick = new Imagick($orig);
    $webMagick->thumbnailImage(770, 1400, true);
    $webMagick->setImageCompressionQuality(60);
    $webMagick->setFormat('JPEG');
    $webMagick->writeImage($web);
    chmod($web, 0444);

    $thumbMagick = new Imagick($orig);
    $thumbMagick->thumbnailImage(256, 256, true);
    $thumbMagick->setImageCompressionQuality(60);
    $thumbMagick->setFormat('JPEG');
    $thumbMagick->writeImage($thumbnail);
    chmod($thumbnail, 0444);

    $db->endTransaction();

    return ['status' => 201];
};
$imageEndpoint->setAddMethod(new Role('editor'), $addImage);

$deleteImage = function (Skautis $skautis, array $data) use ($CONFIG): array {
    $SQL = <<<'SQL'
DELETE FROM `images`
WHERE `id` = :id
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'image');

    $db = new Database;
    $db->beginTransaction();

    $db->prepare($SQL);
    $uuidBin = $id->getBytes();
    $db->bindParam(':id', $uuidBin, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException('image');
    }

    $db->endTransaction();

    unlink($CONFIG->imagepath.'/original/'.$id->toString().'.jpg');
    unlink($CONFIG->imagepath.'/web/'.$id->toString().'.jpg');
    unlink($CONFIG->imagepath.'/thumbnail/'.$id->toString().'.jpg');

    return ['status' => 200];
};
$imageEndpoint->setDeleteMethod(new Role('administrator'), $deleteImage);
