<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Console\Command;

use Maddlen\Zermatt\App\LockFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpLockFile extends Command
{
    public function __construct(
        protected readonly LockFile $lockFile,
        ?string                     $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('zermatt:lock:dump');
        $this->setDescription('Dump zermatt-lock.json files in themes.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->lockFile->dump();
        return Command::SUCCESS;
    }
}
