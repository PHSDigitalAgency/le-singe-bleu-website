<?php
class UserDefinedFormExtension extends DataExtension{


	public function updateCMSfields(FieldList $fields){
		
		$fields->removeByName('Fields_original');

		/*
		 * Hack Submission
		 */
		$fields->removeByName('Submissions');

		// view the submissions
		$submissions = new GridField(
			"Reports", 
			_t('UserDefinedForm.SUBMISSIONS', 'Submissions'),
			 $this->owner->Submissions()->sort('Created', 'DESC')
		);

		// make sure a numeric not a empty string is checked against this int column for SQL server
		$parentID = (!empty($this->owner->ID)) ? $this->owner->ID : 0;

		// get a list of all field names and values used for print and export CSV views of the GridField below.
		$columnSQL = <<<SQL
SELECT "Name", "Title"
FROM "SubmittedFormField"
LEFT JOIN "SubmittedForm" ON "SubmittedForm"."ID" = "SubmittedFormField"."ParentID"
WHERE "SubmittedForm"."ParentID" = '$parentID'
ORDER BY "Title" ASC
SQL;
		$columns = DB::query($columnSQL)->map();

		$config = new GridFieldConfig();
		$config->addComponent(new GridFieldToolbarHeader());
		$config->addComponent($sort = new GridFieldSortableHeader());
		$config->addComponent($filter = new UserFormsGridFieldFilterHeader());
		$config->addComponent(new GridFieldDataColumns());
		$config->addComponent(new GridFieldEditButton());
		$config->addComponent(new GridState_Component());
		$config->addComponent(new GridFieldDeleteAction());
		$config->addComponent(new GridFieldPageCount('toolbar-header-right'));
		$config->addComponent($pagination = new GridFieldPaginator(25));
		$config->addComponent(new GridFieldDetailForm());
		$config->addComponent($export = new GridFieldExportButton());
		$config->addComponent($print = new GridFieldPrintButton());

		$sort->setThrowExceptionOnBadDataType(false);
		$filter->setThrowExceptionOnBadDataType(false);
		$pagination->setThrowExceptionOnBadDataType(false);

		// attach every column to the print view form 
		$columns['Created'] = 'Created';
		$filter->setColumns($columns);
			
		// print configuration
		$print->setPrintHasHeader(true);
		$print->setPrintColumns($columns);

		// export configuration
		$export->setCsvHasHeader(true);
		$export->setExportColumns($columns);

		$submissions->setConfig($config);
		$fields->addFieldToTab("Root.Submissions", $submissions);
		/*
		 * End Hack Submission
		 */

		
	}
}

class UserDefinedFormExtension_Controller extends DataExtension {

	public function updateFormActions(FieldList $actions){
		
		// Debug::log($actions->dataFieldByName('process'));

		// $actions->fieldByName('process')->addExtraClass('btn');
		// $actions->removeByName('Form_Form_action_process');

	}
}