<?php
namespace App\Command;

use App\Scheduler\Message\MailStatistics;
use App\Service\ImageService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('lc:excel','permet de récupérer un excel avec les 20 images les plus téléchargées et le nombre de téléchargements par image.')]
final class DownloadXlxStatsCommand extends Command
{   
    public function __construct(
        private MessageBusInterface $bus,
        private HttpClientInterface $client,
        private ImageService $imageService

    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $images = $this->imageService->fetchImages();

        $lines = []; 
        $lines[] = ['Nom', 'Téléchargements'];
        $spreadsheet = new Spreadsheet();
        $activeWorkSheet =  $spreadsheet->getActiveSheet();
        foreach ($images as $image){
            $lines[] = [
                $image['name'],
                $image['hit_count']
            ];
        }
        $activeWorkSheet->fromArray($lines);
        $writer = new Xlsx($spreadsheet);
        $filename = sys_get_temp_dir() . "/statistiques.xlsx";
        $writer->save($filename);

        return Command::SUCCESS;
    }
}
