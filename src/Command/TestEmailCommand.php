<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-email',
    description: 'Send a test email'
)]
class TestEmailCommand extends Command
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    protected function configure()
    {
        // Plus besoin de définir le nom et la description ici, c'est fait par l'attribut
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (new Email())
            ->from(new Address('admin@exemple.com', 'Admin'))
            ->to('camille@exemple.com') // Remplace par une adresse email de test
            ->subject('Test Email')
            ->text('This is a test email.');

        $this->logger->info('Sending test email');

        try {
            $this->mailer->send($email);
            $this->logger->info('Test email sent successfully');
        } catch (\Exception $e) {
            $this->logger->error('Failed to send test email: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
