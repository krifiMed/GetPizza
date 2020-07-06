<?php

namespace App\Controller;


use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController {

    /**
     * @Route("/register", name="app_register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder,\Swift_Mailer $mailer) {
        // 1) build the form
        $user = new User();
        $validation = false ;
        $error = '';
       // $form = $this->createForm(UserType::class, $user);
        // 2) handle the submit (will only happen on POST)
       // $form->handleRequest($request);
       // if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
        if ($request->isMethod('POST')) {
            $password = $passwordEncoder->encodePassword($user, $request->get('password'));
            $user->setPassword($password);
            //on active par défaut
            $user->setIsActive(true);
            $user->setNom($request->get('name'));
            $user->setPrenom($request->get('prenom'));
            $user->setEmail($request->get('email'));
            $user->setTel($request->get('tel'));
            $user->setPhoto('med');

            if($request->get('role')=='gerant'){
                $user->addRole('ROLE_GERANT');
            }else if ($request->get('role')=='client'){
                $user->addRole('ROLE_CLIENT');
            }else {
                $user->addRole('ROLE_ADMIN');
            }



            if($request->files->get('photo') !=null){
                $uploadedFile = $request->files->get('photo');
                $destination = $this->getParameter('kernel.project_dir').'/public/theme/images/';
                //   var_dump($uploadedFile);
                // $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'-'.$uploadedFile->getClientOriginalName();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setPhoto($newFilename);
            }else {
                $user->setPhoto('profil.png');
            }


            if($request->get('name')=='' ||$request->get('prenom')=='' || $request->get('tel')=='' || $request->get('email')=='' ||$request->get('password')=='' )

            {
                $validation=true ;
                $error = 'il existe des champs vides' ;
                $this->addFlash('error', $error);

            }

            else {
                // 4) save the User!



                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                try {
                $entityManager->flush();
                // ... do any other work - like sending them an email, etc
                // maybe set a "flash" success message for the user

                $message = (new \Swift_Message('Hello Email'))
                ->setFrom('krifi.med@gmail.com')
                 ->setTo('medkr91@outlook.fr')
                 
                 ->setBody(
                     " Bienvenue sur notre plateforme GetPizza " 
                 );
               $mailer->send($message);

                }
                catch (UniqueConstraintViolationException $e){
                    if($e->getCode() === '23000'){
                        $this->addFlash('error', 'Votre compte à bien été enregistré.');
                    }


                }
                $this->addFlash('success', 'Votre compte à bien été enregistré.');

                $url = $this->generateUrl('login');
                return $this->redirect($url);
            }


        }
        return $this->render('registration/index.html.twig');
    }

}
