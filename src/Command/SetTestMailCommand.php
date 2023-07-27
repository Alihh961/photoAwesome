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

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $response = $this->httpClient->request(
            "GET",
            "https:/api.brevo.com/v3/smtp/email",
            [
                "headers" => [
                    "accept" => "application/json", // we only receive reponse in json format
                    "api-key" => "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",
                    "content-type" => "application/json" // we only send data in json format
                ],
                "json" => [ //content
                    "sender" => [
                        "name" => "Ali",
                        "email" => "hajhassan.ali@outlook.com",

                    ],
                    "to" => [
                        "name" => "AliHaj",
                        "email" => "hajhassan.ali92@gmail.com"
                    ],
                    "subject"=>"Helloz!!!!",
                    "htmlContent"=> "<p style='background-color:red;width:fit-content'>Hello there</p>"
                ]
            ]
        );


        return Command::SUCCESS;
    }
}
