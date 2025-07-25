<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Nationality;
use App\Entity\Review;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        
        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
            //     return $this->redirectToRoute('...');
            // }
            
            // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
            // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
            //
            // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony Project Book');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Users', 'fa fa-user');
        yield MenuItem::linkToCrud('Authors', 'fa fa-person-walking-with-cane', Author::class);
        yield MenuItem::linkToCrud('Books', 'fa fa-book-open', Book::class);
        yield MenuItem::linkToCrud('Reviews', 'fa fa-comment', Review::class);
        yield MenuItem::linkToCrud('Nationalities', 'fa fa-flag', Nationality::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);




        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
