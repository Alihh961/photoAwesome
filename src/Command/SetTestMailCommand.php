<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:set-test-mail',
    description: 'Add a short description for your command',
)]
class SetTestMailCommand extends Command
{

    public function __construct(private HttpClientInterface $httpClient)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
//        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
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $response = $this->httpClient->request('POST', "https://api.brevo.com/v3/smtp/email", [
            'headers' => [
                'accept' => 'application/json',
                'api-key' => "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",
                'content-type' => 'application/json'
            ],
            'json' => [
                "sender" => [
                    'name' => "Kadidja Tiaiba",
                    'email' => 'khadidja_du_73@outlook.fr'
                ],
                "to" => [
                    [
                        'email' => 'khadidja_du_73@outlook.fr',
                        'name' => "Khadidja Tiaiba"
                    ],

                ],
                "subject" => "Bonjour !!!!",
                "htmlContent" => "<html><head></head><body ><p style='width:fit-content;background-color:red;color:white'>Wesh,</p>Regarde le mail.</p></body></html>"
            ]
        ]);


        return Command::SUCCESS;
    }
}
