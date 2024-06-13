<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Classe\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;

class OrderCrudController extends AbstractCrudController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Afficher')->linkToCrudAction('show');
        return $actions
    ->add(Crud::PAGE_INDEX, $show)
    ->remove(Crud::PAGE_INDEX, Action::NEW)
    ->remove(Crud::PAGE_INDEX, Action::EDIT)
    ->remove(Crud::PAGE_INDEX, Action::DELETE);
           
    }

    // fonction permettant le changement de staut de commande
    public function changeState($order,$state)
    {
        //1 modification du statut de la commande
        $order->setState($state);
        $this->em->flush();

        //2 affichage du flash message
        $this->addFlash('success','Statut de la commande correctement  mis à jour.');

        //3 informer l'utilisateur du statut de la commande
        
        $mail = new Mail();
        $vars = [
            'firstname'=> $order-> getUser()->getFirstname(),
            'id_order'=> $order->getId()
        ];
        $mail->send($order->getUser()->getEmail(),$order-> getUser()->getFirstname().' ' .$order-> getUser()->getLastname(), State::STATE[$state]['email_subject'], State::STATE[$state]['email_template'], $vars );
 
    }

    public function show(AdminContext $context, AdminUrlGenerator $adminUrlGenerator, Request $request)
    {
         $order = $context->getEntity()->getInstance();
        // récupérer l'URL de notre action show 
        $url = $adminUrlGenerator->setController(self::class)->setAction('show')->setEntityId($order->getId())->generateUrl();
       
        // traitement de changement des statuts
        if($request->get('state')){
            $this->changeState($order,$request->get('state'));
        }

        return $this->render('admin/order.html.twig', [
            'order'=> $order,
            'current_url'=> $url
        ]);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            NumberField::new('state')->setLabel('Statut')->setTemplatePath('admin/state.html.twig'),
            TextField::new('carrierName')->setLabel('Transporteur'),
            AssociationField::new('user')->setLabel('Utilisateur'),
            NumberField::new('totalTva')->setLabel('Total TVA'),
            NumberField::new('totalWt')->setLabel('Total TTC')
           
        ];
    }

}
