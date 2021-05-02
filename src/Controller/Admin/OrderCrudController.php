<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        $updateOrder = Action::new('updateOrder', 'En cours de préparation', 'fas fa-truck-loading')
            ->linkToCrudAction('updateOrder');
        $updateDelivery = Action::new('updateDelivery', 'En cours de livraison', 'fas fa-shipping-fast')
            ->linkToCrudAction('updateDelivery');
        return $actions
            ->add('detail', $updateOrder)
            ->add('detail', $updateDelivery)
            ->add('index', 'detail');
    }

    public function updateOrder(AdminContext $adminContext){
        $order = $adminContext->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash(
            'notification',
            "<span class='alert-info'>
                        <strong>En cours de préparation : Commande ".$order->getReference()."</strong>
                     </span>"
        );

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
        //envoi email pour anoncer le changement de status de la commande
        return $this->redirect($url);
    }
    public function updateDelivery(AdminContext $adminContext){
        $order = $adminContext->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash(
            'notification',
            "<span class='alert-warning'>
                        <strong>En cours de livraison : Commande ".$order->getReference()."</strong>
                     </span>"
        );

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
        //envoi email pour anoncer le changement de status de la commande
        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user.fullname', 'Utilisateur' ),
            TextEditorField::new('delivery', 'Adresse de Livraison')->onlyOnDetail(),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('carrierName', 'Type de transport'),
            MoneyField::new( 'carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Status')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
        ];
    }

}
