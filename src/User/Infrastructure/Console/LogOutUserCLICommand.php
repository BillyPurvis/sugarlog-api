<?php

namespace App\User\Infrastructure\Console;

use App\User\Domain\Command\LogOutUserCommand;
use App\User\Domain\Command\RegisterUserCommand;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogOutUserCLICommand extends Command
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

        $this->setName('app:logout-user')
            ->setDescription('Logs out a user')
            ->setHelp('This command logouts a user using LogOutUserCommandHandler')
            ->addArgument('username', InputArgument::REQUIRED, 'User Username');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Format Style
        $style = new OutputFormatterStyle('black', 'green');
        $output->getFormatter()->setStyle('earth', $style);

        $username = $input->getArgument('username');

        $this->commandBus->handle(new LogOutUserCommand($username));

        $output->writeln([
            '<earth>User Successfully Logged Out',
            '============================',
            '                            ',
            'Username: '. $username . '            </>'
        ]);


    }
}