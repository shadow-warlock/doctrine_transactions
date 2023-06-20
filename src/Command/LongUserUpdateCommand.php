<?php

namespace App\Command;

use App\Entity\User;
use App\Enum\Sex;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:update',
    description: 'Долго обновляет имена двум пользователям ожидая между 10 секунд.
     Для вызова лока: обновите 2 параллельными вызовами пользователя с id = 1 (из fixtures) первым аргументом
     Для вызова дедлока: обновите 2 параллельными вызовами command1(1 kolebn1 2 timager1) и command2(2 timager2 1 kolebn2)
     * значения поля name в команде должны отличаться от имеющихся в базе, иначе доктрина не пошлет запрос'
)]
class LongUserUpdateCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED)
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('id2', InputArgument::REQUIRED)
            ->addArgument('name2', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Команда запущена');
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $user1 = $this->userRepository->find($input->getArgument('id'));
            $user1->setName($input->getArgument('name'));
            $this->em->persist($user1);
            $this->em->flush();

            for ($i = 0; $i < 10; $i ++) {
                sleep(1);
                $output->write('.');
            }
            $output->writeln('');

            $user2 = $this->userRepository->find($input->getArgument('id2'));
            $user2->setName($input->getArgument('name2'));
            $this->em->persist($user2);
            $this->em->flush();

            $this->em->getConnection()->commit();
        } catch (Exception $e) {
            $this->em->getConnection()->rollBack();
            $output->writeln('Ошибка: ' . $e->getMessage());
            return Command::FAILURE;
        }
        $output->writeln('Команда закончена');
        return Command::SUCCESS;
    }
}