<?php

namespace AppBundle\Security\Core\User;


use AppBundle\Entity\User;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class MyFOSUBUserProvider  extends BaseFOSUBProvider
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        // get property from provider configuration by provider name
        // , it will return `facebook_id` in that case (see service definition below)
        $property = $this->getProperty($response);
        $username = $response->getUsername(); // get the unique user identifier

        //we "disconnect" previously connected users
        $existingUser = $this->userManager->findUserBy(array($property => $username));
        if (null !== $existingUser) {
            // set current user id and token to null for disconnect
            // ...

            $this->userManager->updateUser($existingUser);
        }
        // we connect current user, set current user id and token
        // ...
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userEmail = $response->getEmail();
        $user = $this->userManager->findUserByEmail($userEmail);

        // if null just create new user and set it properties
        if (null === $user) {
            $data = $response->getResponse();
            if($data['gender']=="male"){
                $genre = 1;
            }else{
                $genre = 0;
            }


            $username = $response->getRealName();
            $user = new User();


            $secret = md5(uniqid(rand(), true));
            $user->setPassword($secret);

            $user->setUsername($response->getEmail());
            $user->setEmail($response->getEmail());
            $user->setNom($response->getLastName());
            $user->setPrenom($response->getFirstName());
            $user->setDnaiss(new \DateTime($data['birthday']));
            $user->setGenre($genre);
            $user->setImageUser($response->getProfilePicture());
            $user->setTel(null);
            $user->setEnabled(true);


            $this->userManager->createUser();

            // ... save user to database

            return $user;
        }
        // else update access token of existing user
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());//update access token

        return $user;
    }
}