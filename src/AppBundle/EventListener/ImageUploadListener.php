<?php
/**
 * Created by PhpStorm.
 * User: Mbape
 * Date: 18/03/2018
 * Time: 15:05
 */

namespace AppBundle\EventListener;

use AppBundle\AppBundle;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Plat;
use AppBundle\Entity\User;
use AppBundle\ImageUpload;

class ImageUploadListener implements EventSubscriber
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

    /**
     * @ORM\PreUpdate
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {

        if($args->hasChangedField('imageUser')){
            $oldImage = $args->getOldValue('imageUser');
        }

        if(isset($oldImage) && $args->getNewValue('imageUser')==null){
            $entity = $args->getEntity();
            $entity->setImageUser($oldImage);
            $this->uploadFile($entity);

        }else{
            $entity = $args->getEntity();
            $this->uploadFile($entity);
        }


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

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }
}