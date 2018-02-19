<?php

namespace App\User\Infrastructure\Console;

use App\User\Domain\Command\RegisterUserCommand;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterUserCLICommand extends Command
{
    /**
     * @var CommandBus $commandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
       parent::configure();

        $this->setName('app:register-user')
            ->setDescription('Registeres a new user')
            ->setHelp('This command registers a user using RegisterUserCommandHandler')
            ->addArgument('username', InputArgument::REQUIRED, 'User Username')
            ->addArgument('email', InputArgument::REQUIRED, 'User Email')
            ->addArgument('password', InputArgument::REQUIRED, 'User Password');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Format Style
        $style = new OutputFormatterStyle('black', 'green');
        $output->getFormatter()->setStyle('earth', $style);


        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');


        $this->commandBus->handle(new RegisterUserCommand($username, $email, $password));

        $output->writeln([
            '<earth>User Successfully Registered',
            '============================',
            '                            ',
            'Username: '. $username . '            </>'
        ]);


    }
}