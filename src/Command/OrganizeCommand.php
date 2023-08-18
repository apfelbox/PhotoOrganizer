<?php declare(strict_types=1);

namespace App\Command;

use App\Action\OrganizeAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OrganizeCommand extends Command
{
	/**
	 * @inheritDoc
	 */
	protected function configure ()
	{
		parent::configure();
	}

	/**
	 * @inheritDoc
	 */
	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title("Photos: organize");

		$action = new OrganizeAction();
		$action->organizeFiles(\getcwd());

		return 0;
	}
}
