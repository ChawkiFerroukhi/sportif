<?php
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.senteurs-elysee.com/application/api/'.$_ENV['APP_SECRET']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        var_dump($response);
        // If using JSON...
        $data = json_decode($response);
        var_dump($data);
        if(!$data->active) {
            throw new CustomUserMessageAuthenticationException(
                'Application verrouillée. Veuillez contactez le manager.'
            );
        }
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getIsActive()) {
            throw new CustomUserMessageAuthenticationException(
                'Compte inactif'
            );
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        $this->checkPreAuth($user);
    }
}
