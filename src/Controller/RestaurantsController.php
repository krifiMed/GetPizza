<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Pizza;
use App\Entity\Restaurant;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client", name="client")
 */
class RestaurantsController extends AbstractController
{
    /**
     * @Route("/", name="client")
     */
    public function index()
    {
       $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $restaurants = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->findAll();


        // the template path is the relative file path from `templates/`
        return $this->render('Restaurant/restaus.html.twig', [
            // this array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
            'restaus' => $restaurants,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/mesrestaurants", name="client")
     */
    public function mesRestaurants()
    {
         $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $restaurants = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->findAll();


        // the template path is the relative file path from `templates/`
        return $this->render('Restaurant/mesrestaus.html.twig', [
            // this array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
            'restaus' => $restaurants,
             'user' => $user,
        ]);
    }

    /**
     * @Route("/supp/{id}", name="delete_restau")
     */
    public function removeRestaurant(int $id,Request $request)
    {
        $restau = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        $em =$this->getDoctrine()->getManager();
        $em->remove($restau);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Restaurant Supprimé avec succées ...!');

        $url = $this->generateUrl('mesRestaurants');
        return $this->redirect($url);

       // return $this->render("product/product.html.twig", [
       //     "product" => $product,
       // ]);
    }

    /**
     * @Route("/add", name="add_restau")
     */
    public function addRestaurant(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $validation = false;
        $error = '' ;

           //  var_dump($user->getId());

        if ($request->isMethod('POST')) {

            $restau = new Restaurant();
            $restau->setName($request->get('name'));
            $restau->setDescription($request->get('description'));
            $restau->setAdresse($request->get('adresse'));
            $restau->setTel($request->get('tel'));

            $restau->setIduser($user->getId());

            if($request->get('name')=='' ||$request->get('tel')=='' || $request->get('adresse')=='' || $request->get('description')=='' || $request->files->get('photo')== null )

            {

                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'il existe des champs vides...!');

            }
            else {

                //  $data = $this->getRequest()->request->all();
                //  $file = $this->getRequest()->files->get('file');

                /** @var UploadedFile $uploadedFile */
                var_dump($request->files->get('photo'));
                $uploadedFile = $request->files->get('photo');
                $destination = $this->getParameter('kernel.project_dir').'/public/theme/images/';
                var_dump($uploadedFile);
                // $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'-'.$uploadedFile->getClientOriginalName();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $restau->setPhoto($newFilename);

                $em = $this->getDoctrine()->getManager();
                $em->persist($restau);
                $em->flush();

                $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Restaurant Ajouté avec succées ...!');

                $url = $this->generateUrl('mesRestaurants');
                return $this->redirect($url);
            }
        }

         return $this->render("Restaurant/add.html.twig", [
           //  "product" => $product,
         ]);
    }

    /**
     * @Route("/modif/{id}", name="modif_restau")
     */
    public function modifRestaurant($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $restau = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        //  var_dump($user->getId());

        if ($request->isMethod('POST')) {


            $restau->setName($request->get('name'));
            $restau->setDescription($request->get('description'));
            $restau->setAdresse($request->get('adresse'));
            $restau->setTel($request->get('tel'));

            $restau->setIduser($user->getId());

            //  $data = $this->getRequest()->request->all();
            //  $file = $this->getRequest()->files->get('file');

            if($request->get('name')=='' ||$request->get('tel')=='' || $request->get('adresse')=='' || $request->get('description')==''  )

            {

                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'il existe des champs vides...!');

            }
            else {

             if($request->files->get('photo')!= null)   {
                 $uploadedFile = $request->files->get('photo');
                 $destination = $this->getParameter('kernel.project_dir').'/public/theme/images/';
                 var_dump($uploadedFile);
                 // $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                 $newFilename = uniqid().'-'.$uploadedFile->getClientOriginalName();
                 $uploadedFile->move(
                     $destination,
                     $newFilename
                 );
                 $restau->setPhoto($newFilename);
             }
            /** @var UploadedFile $uploadedFile */

            $em = $this->getDoctrine()->getManager();
            $em->persist($restau);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Restaurant Modifier avec succées ...!');

            $url = $this->generateUrl('mesRestaurants');
            return $this->redirect($url);
            }

        }


        return $this->render("Restaurant/modif.html.twig", [
              "r" => $restau,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details_restau")
     */
    public function detailsRestaurant(int $id)
    {
        $restau = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        $pizza = $this->getDoctrine()->getRepository(Pizza::class)->findBy([

            "idRestaurant"=>$id,

        ]);

      // $em =$this->getDoctrine()->getManager();
      // $em->remove($restau);
      // $em->flush();


        return $this->render("Restaurant/detailsRestauGerant.html.twig", [
            "r" => $restau,
            "pizza"=>$pizza
        ]);
    }


    /**
     * @Route("/addPizza/{id}", name="add_pizza_restau")
     */
    public function addPizzaToRestaurant($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {

           $pizza = new Pizza();
           $pizza->setName($request->get('name'));
           $pizza->setDescription($request->get('description'));
           $pizza->setPrice($request->get('price'));
           $pizza->setIdRestaurant($id);


            if($request->get('name')=='' ||$request->get('description')=='' || $request->get('price')=='' || $request->files->get('photo')== null )
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'il existe des champs vides ...!');

            }

            else {


                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $request->files->get('photo');
                $destination = $this->getParameter('kernel.project_dir') . '/public/theme/images/';
                $newFilename = uniqid() . '-' . $uploadedFile->getClientOriginalName();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $pizza->setPhoto($newFilename);


                $em = $this->getDoctrine()->getManager();
                $em->persist($pizza);
                $em->flush();

                $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Pizza Ajouté avec succées ...!');

                $url = $this->generateUrl('details_restau', ['id' => $id]);
                return $this->redirect($url);
            }

        }

        return $this->render("Restaurant/ajoutPizza.html.twig", [
            //  "product" => $product,
        ]);
    }

    /**
     * @Route("/supp_pizza/{id}", name="delete_pizza")
     */
    public function removePizzaRestaurant(int $id,Request $request)
    {
        $pizza = $this->getDoctrine()->getRepository(Pizza::class)->find($id);

        $em =$this->getDoctrine()->getManager();
        $em->remove($pizza);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Pizza Supprimé avec succées ...!');

        $url = $this->generateUrl('details_restau', ['id' => $pizza->getIdRestaurant()]);
        return $this->redirect($url);

    }

    /**
     * @Route("/modifPizza/{id}", name="modif_pizza_restau")
     */
    public function modifPizzaToRestaurant($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $pizza = $this->getDoctrine()->getRepository(Pizza::class)->find($id);
        if ($request->isMethod('POST')) {


            $pizza->setName($request->get('name'));
            $pizza->setDescription($request->get('description'));
            $pizza->setPrice($request->get('price'));

            if($request->get('name')=='' ||$request->get('description')=='' || $request->get('price')==''  )
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'il existe des champs vides ...!');

            }



            else{

                if($request->files->get('photo')!= null){
                    $uploadedFile = $request->files->get('photo');
                    $destination = $this->getParameter('kernel.project_dir').'/public/theme/images/';
                    $newFilename = uniqid().'-'.$uploadedFile->getClientOriginalName();
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $pizza->setPhoto($newFilename);
                }
            /** @var UploadedFile $uploadedFile */

            $em = $this->getDoctrine()->getManager();
            $em->persist($pizza);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Pizza Modifier avec succées ...!');

            $url = $this->generateUrl('details_restau', ['id' => $pizza->getIdRestaurant()]);
            return $this->redirect($url);
            }

        }

        return $this->render("Restaurant/modifPizza.html.twig", [
             "p" => $pizza,
        ]);
    }

    /**
     * @Route("/detailsClient/{id}", name="details_restau_client")
     */
    public function detailsRestaurantClient(int $id)
    {
        $restau = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        $pizza = $this->getDoctrine()->getRepository(Pizza::class)->findBy([

            "idRestaurant"=>$id,

        ]);

        // $em =$this->getDoctrine()->getManager();
        // $em->remove($restau);
        // $em->flush();


        return $this->render("Restaurant/detailsRestautClient.html.twig", [
            "r" => $restau,
            "pizza"=>$pizza
        ]);
    }

    /**
     * @Route("/commanderClient/{id}", name="details_restau_client")
     */
    public function commanderClient(int $id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $restau = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        $pizza = $this->getDoctrine()->getRepository(Pizza::class)->findBy([

            "idRestaurant"=>$id,

        ]);


        if ($request->isMethod('POST')) {

            $commande = new Commande();
            // cle etrangére
            $commande->setIdRestaurant($restau) ;
            $commande->setIdUser($user);

            $commande->setQuantite($request->get('quantite'));
            $commande->setName($request->get('name'));
            $commande->setType($request->get('type'));

            $commande->setTraite(0);
            $commande->setDate(new \DateTime("now"));


            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Votre commande a été Commande Ajouté avec succées ...!');

            $url = $this->generateUrl('mesCommandes', ['id' => $id]);
            return $this->redirect($url);



        }


        return $this->render("Restaurant/commanderClient.html.twig", [
            "r" => $restau,
            "pizza"=>$pizza
        ]);
    }


    /**
     * @Route("/detailsClient", name="details_restau_client")
     */
    public function mesCommandesClient()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findBy([

        "idUser"=>$user->getId(),

    ],
            ['date' => 'DESC']);

        return $this->render("Restaurant/mesCommandes.html.twig", [
            "commandes" => $commande,
        ]);
    }

    /**
     * @Route("/detailsClient", name="details_restau_client")
     */
    public function mesCommandesGerant()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $restaus = $this->getDoctrine()->getRepository(Restaurant::class)->findBy([

            "iduser"=>$user->getId(),

        ]);

        $d = array() ;

        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();


        foreach ($restaus as $r){
            foreach ($commandes as $c){
                if($r->getId() == $c->getIdRestaurant()->getId() ) array_push($d,$c);
            }

        }

    //   var_dump($d);

        return $this->render("Restaurant/mescommandesGerant.html.twig", [
            "commandes" => $d,
        ]);
    }


    /**
     * @Route("/supp/{id}", name="delete_restau")
     */
    public function traiteCommande(int $id,Request $request,\Swift_Mailer $mailer)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);

        $commande->setTraite(1) ;

        $em =$this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

       $message = (new \Swift_Message('Hello Email'))
        ->setFrom('krifi.med@gmail.com')
         ->setTo('medkr91@outlook.fr')
         
         ->setBody(
             "Votre commande a été traité avec succès" 
         );
       $mailer->send($message);


     //   $basic  = new \Nexmo\Client\Credentials\Basic('8c90bab6', 'pVzWL5WUsCMkXm8N');
     //   $client = new \Nexmo\Client($basic);
     //
     //   $message = $client->message()->send([
     //       'to' => '21627701683',
     //       'from' => 'Vonage APIs',
     //       'text' => 'Hello from Vonage SMS API'
     //   ]);

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Commande traité avec succées ...!');

        $url = $this->generateUrl('mesCommandesGerant');
        return $this->redirect($url);

    }


    /**
     * @Route("/detailsClient", name="details_restau_client")
     */
    public function monprofilClient(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $validation= false ;
        $error = '';

        if ($request->isMethod('POST')) {
       //     var_dump($request->get('prenom'));
            $user->setPrenom($request->get('prenom'));
            $user->setNom($request->get('name'));
            $user->setTel($request->get('tel'));
            $user->setEmail($request->get('email'));

            if($request->get('password') !='') {
                $password = $passwordEncoder->encodePassword($user, $request->get('password'));
                $user->setPassword($password);
            }

          //  var_dump($request->files->get('photo'));

            if($request->files->get('photo') !=null){
                $uploadedFile = $request->files->get('photo');
                $destination = $this->getParameter('kernel.project_dir').'/public/theme/images/';
                //  var_dump($uploadedFile);
                // $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'-'.$uploadedFile->getClientOriginalName();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setPhoto($newFilename);
            }

            if($request->get('name')=='' ||$request->get('prenom')=='' || $request->get('tel')=='' || $request->get('email')==''  )

            {
                $validation=true ;
                $error = 'il existe des champs vides' ;
                $this->addFlash('error', $error);

            }

            else {

             $em =$this->getDoctrine()->getManager();
             $em->persist($user);
             $em->flush();



            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Profil modifier avec succées ...!');

            $url = $this->generateUrl('profil_client', ['id' => $user->getId()]);
            return $this->redirect($url);
            }

        }


        return $this->render("Restaurant/profilClient.html.twig", [
            "user" => $user,
        ]);
    }

    /**
     * @Route("/detailsClient", name="details_restau_client")
     */
    public function voirprofil($id)
    {
        $user2 = $this->container->get('security.token_storage')->getToken()->getUser();

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        // if ($request->isMethod('POST')) {


        return $this->render("Restaurant/profilGerant.html.twig", [
            "user" => $user,
            "user2"=>$user2
        ]);
    }


    /**
     * @Route("/detailsClient", name="details_restau_client")
     */
    public function clientAdmin()
    {
        $user2 = $this->container->get('security.token_storage')->getToken()->getUser();

        $clients = $this->getDoctrine()->getRepository(User::class)->findAll();

        $d = array() ;

        foreach ($clients as $c ) {
           if( $c->getRoles()[0] =='ROLE_CLIENT') {
                array_push($d,$c);
            }
        }

        return $this->render("Restaurant/clientAdmin.html.twig", [
            "clients" => $d,
            "user"=>$user2
        ]);
    }

    /**
     * @Route("/detailsClient", name="details_restau_client")
     */
    public function gerantAdmin()
    {
        $user2 = $this->container->get('security.token_storage')->getToken()->getUser();

        $clients = $this->getDoctrine()->getRepository(User::class)->findAll();

        $d = array() ;

        foreach ($clients as $c ) {
            if( $c->getRoles()[0] =='ROLE_GERANT') {
                array_push($d,$c);
            }
        }
        return $this->render("Restaurant/gerantAdmin.html.twig", [
            "clients" => $d,
            "user"=>$user2
        ]);
    }



    /**
     * @Route("/supp/{id}", name="delete_restau")
     */
    public function removeClient(int $id,Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em =$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Client Supprimé avec succées ...!');

        $url = $this->generateUrl('client_admin');
        return $this->redirect($url);

    }


    /**
     * @Route("/supp/{id}", name="delete_restau")
     */
    public function removeGerant(int $id,Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em =$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Gerant Supprimé avec succées ...!');

        $url = $this->generateUrl('gerant_admin');
        return $this->redirect($url);

    }

}
