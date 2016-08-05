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
	const SAVE_DIR = "../www/content/background/";

	/**
	 * @var Model\Background
	 * @inject
	 */
	public $background;

	public function renderDefault()
	{
		$this->template->background = $this->background;
	}

	/**
	 * Create form for uploading images
	 * @return Form
	 */
	protected function createComponentBackgroundForm()
	{
		$form = new UI\Form();

		$form->addUpload('image', 'Obrázek JPG')
			->addRule($form::IMAGE, 'Vyberte prosím obrázek JPG')
			->addRule($form::MAX_FILE_SIZE, 'Obrázky nesmí přesáhnout 2MB', 2250000)
			->setRequired();

		$form->addSubmit('send', 'Nahrát');

		$form->onSuccess[] = array($this, 'backgroundFormSucceeded');

		return $form;
	}

	public function backgroundFormSucceeded(UI\Form $form)
	{
		$values = $form->getValues();

		/** @var FileUpload $uploadedImage */
		$uploadedImage = $values['image'];
		$this->background->insert(['id'=>null]);

		$id = 1;
		foreach ($this->background->createSelectionInstance()->order('id DESC')->limit(1) as $row) {
			$id = $row->id;
		}

		try {
			$uploadedImage->move(self::SAVE_DIR . $id . '.jpg');
			$this->flashMessage('Pozadí je nahráno.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Pozadí se nepodařilo uložit.', 'error');
		}

		$this->redirect('this');
	}

	/**
	 * Delete Article by id
	 *
	 * @param $id
	 */
	public function actionDelete($id)
	{
		if (!preg_match("/^[0-9]*$/",$id)) {
			$this->flashMessage('Pozadí nenalezeno.', 'error');
			$this->redirect('default');
		} else {

			$this->background->where(['id'=>$id])->delete();
			@unlink(self::SAVE_DIR . $id . '.jpg');
			$this->flashMessage('Pozadí odstraněno.', 'success');
			$this->redirect('default');
		}
	}
}
