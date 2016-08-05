<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Settings of webpages
 */
class SettingPresenter extends BasePresenter
{
	/**
	 * @var Model\Setting
	 * @inject
	 */
	public $setting;

	/**
	 * @var Model\Article
	 * @inject
	 */
	public $article;


	public function renderDefault()
	{
		$this->template->setting = $this->setting;
	}
	
	/**
	 * @param $id
	 */
	public function actionEdit($id) {
		$row = $this->setting->get($id);
		if(!$row) {
			$this->error('Data nebyla nalezena v databázi.', 404);
		}
		$defaults = $row->toArray();
		$this['settingForm']->setDefaults($defaults);

	}

	protected function createComponentSettingForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addText('key', 'Klíč:')
			->setRequired();

		$form->addTextArea('value', 'Hodnota:');

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'settingFormSucceeded');

		return $form;
	}

	public function settingFormSucceeded(UI\Form $form, $values)
	{
		$settingData = $values;
		$settingId = $this->getParameter('id');

		if ($settingId) {
			$setting = $this->setting->get($settingId);
			if (!$setting) {
				$this->error('Data nebyla nalezena v databázi.', '404');
			} else {
				try {
					$setting->update($settingData);
				} catch (\Exception $e) {
					$this->flashMessage('Nové nastavení nelze uložit, klíč "'. $values['key'] .'" je již používán."', 'danger');
					$this->redirect('this');
				}
			}
			$this->flashMessage('Změny v nastavení uloženy.', 'success');
		} else {
			try {
				$setting = $this->setting->insert($settingData);
			} catch (\Exception $e) {
				$this->flashMessage('Nové nastavení nelze uložit, klíč "'. $values['key'] .'" je již používán."', 'danger');
				$this->redirect('this');
			}
			$this->flashMessage('Nové nastavení uloženo.', 'success');
		}

		$this->redirect('default');
	}

	/**
	 * Delete setting by id
	 *
	 * @param $id
	 */
	public function actionDelete($id)
	{
		$this->setting->get($id)->delete();
		$this->flashMessage('Nastavení odstraněno.', 'success');
		$this->redirect('default');
	}


	protected function createComponentFirstPageForm()
	{
		$form = new UI\Form();
		$menu = array();
		foreach ($this->article->select('id, menu') as $a) {
			$menu[$a->id] = $a->menu;
		}
		$menu[''] = '--- Prázdná stránka ---';
		$form->addSelect('first_page_id', 'První stránka', $menu);

		$form->addSubmit('send', 'Uložit');

		$form->onSuccess[] = array($this, 'firstPageFormSucceeded');

		return $form;
	}

	public function firstPageFormSucceeded(UI\Form $form, $values)
	{
		$val = $values['first_page_id'] ?  $values['first_page_id'] : null;
		$row = null;
		foreach ($this->setting->createSelectionInstance()->where(['key' => 'first_page_id']) as $i) {
			$row = $i;
		}

		if ($row) {
			// update
			$row->update(['value'=>$val]);
		} else {
			//insert
			$this->setting->insert(['key'=>'first_page_id', 'value'=>$val]);
		}

		$this->flashMessage('Byla nastavena První strana.', 'success');
		$this->redirect('default');

	}

	public function actionFirstPage()
	{
		$firstPageId = $this->setting->getByKey('first_page_id');
		$article = $this->article->createSelectionInstance()->get($firstPageId);
		$defaults['first_page_id'] = ($article) ? $article->id : '';
		$this['firstPageForm']->setDefaults($defaults);
	}
}