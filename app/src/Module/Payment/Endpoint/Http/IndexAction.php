<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Http;

use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

final readonly class IndexAction
{
    public function __construct(
        private ViewsInterface $views,
    ) {}

    #[Route(
        route: '/',
        methods: ['GET'],
        group: 'web',
    )]
    public function index(): string
    {
        return $this->views->render('index');
    }
}
