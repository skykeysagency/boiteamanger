<?php
/**
 * Created by PhpStorm.
 * User: Mbape
 * Date: 18/03/2018
 * Time: 15:05
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Plat;
use AppBundle\Entity\User;
use AppBundle\ImageUpload;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUpload $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if ($entity instanceof Plat || $entity instanceof User) {
            if ($entity instanceof Plat) {
                $file = $entity->getImagePlat();

                // only upload new files
                if (!$file instanceof UploadedFile) {
                    return;
                }

                $fileName = $this->uploader->upload($file);
                $entity->setImagePlat($fileName);
            } else if ($entity instanceof User) {
                $file = $entity->getImageUser();

                // only upload new files
                if (!$file instanceof UploadedFile) {
                    return;
                }

                $fileName = $this->uploader->upload($file);
                $entity->setImageUser($fileName);
            }
        }

        return;
    }
}