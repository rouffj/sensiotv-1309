<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\HttpClient\HttpClient;
use App\Omdb\OmdbClient;

class AppCommand extends Command
{

    private $omdbClient;

    public function __construct()
    {
        parent::__construct('app');

        $this->omdbClient = new OmdbClient(HttpClient::create(), '28c5b7b1', 'https://www.omdbapi.com/');
    }

    protected function configure()
    {
        $this
            ->setDescription('@TODO Add a well documented description of what this command is doing.')
            ->addArgument('keyword', InputArgument::OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!$answer = $input->getArgument('keyword')) {
            $answer = $io->ask('Which movie you are looking for?', 'Sky');
        }

        $result = $this->omdbClient->requestAllBySearch($answer);
        $io->success(sprintf('%s movies found for the keyword %s', $result['totalResults'], $answer));

        $rows = [];
        $io->progressStart(count($result['Search']));
        foreach ($result['Search'] as $movie) {
            $io->progressAdvance();
            usleep(100000);
            $rows[] = [$movie['Title'], $movie['Year'], 'https://www.imdb.com/title/'.$movie['imdbID'].'/'];
        }
        $output->write("\r");
        //$io->progressFinish();

        $io->table(['TITLE', 'RELEASED', 'IMDB URL'], $rows);

        $io->success('Hello from omdb command :)!');

        return 0;
    }
}

