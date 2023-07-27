<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:setAdmin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager , private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $email = $input->getArgument("email");
        $userEntity = $this->userRepository->findOneBy(["email" => $email]);
        if($userEntity != null ){
        $userEntity->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
        $output->writeln("Added with success");
    }else{
            $output->writeln("No user found related to " . $email);
            return command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
