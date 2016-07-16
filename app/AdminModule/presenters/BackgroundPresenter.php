<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Background image manager presenter.
 */
class BackgroundPresenter extends BasePresenter
{
	const FILE_PATH = 'bg/';

	public function renderDefault()
	{

	}

	/* form succeed

	if ($this->saveFile($values['image'], self::FILE_PATH . $article->id . '.jpg')) {
			$this->flashMessage('Obrázek uložen.', 'success');
		}

	$form->addUpload('image', 'Obrázek JPG')
			->addCondition($form::FILLED)
			->addRule($form::IMAGE, 'Zvolený soubor není obrázek.')
			->addRule($form::MAX_FILE_SIZE, 'Maximální velikost souboru je 5 MB.', 6 * 1024 * 1024);

	*/
}
