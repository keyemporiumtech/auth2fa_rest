<?php
App::uses('ExceptionRenderer', 'Error');
App::uses("Codes", "Config/system");

class ApiExceptionRenderer extends ExceptionRenderer {
	const NOT_FOUND= 404;
	const BAD_REQUEST= 400;
	const FORBIDDEN= 403;
	const METHOD_NOT_ALLOWED= 405;
	const INTERNAL_ERROR= 500;
	const NOT_IMPLEMENTED= 501;

	public function redirectError($responsecod, $internalmessage, $error) {
		$filename= basename($error->getFile());
		$exception_message= "[ERROR_CODE]: {$error->getCode()} [DESCRIPTION]: {$error->getMessage()} [FILE]: {$filename} [LINE]: {$error->getLine()}";
		$this->controller->redirect(array (
				'controller' => 'apierrors',
				'action' => 'apierror',
				"?" => array (
						"cod" => Codes::get("INFO_GENERIC"),
						"responsecod" => $responsecod,
						"message" => "",
						"exception" => $exception_message,
						"exceptioncod" => $error->getCode(),
						"internal" => $internalmessage,
						"internalcod" => Codes::get("INTERNAL_GENERIC") 
				) 
		));
	}

	public function fatalError($error) {
		$this->redirectError(ApiExceptionRenderer::NOT_FOUND, "fatalError", $error);
	}

	public function notFound($error) {
		$this->redirectError(ApiExceptionRenderer::NOT_FOUND, "notFound", $error);
	}

	public function badRequest($error) {
		$this->redirectError(ApiExceptionRenderer::BAD_REQUEST, "badRequest", $error);
	}

	public function forbidden($error) {
		$this->redirectError(ApiExceptionRenderer::FORBIDDEN, "forbidden", $error);
	}

	public function methodNotAllowed($error) {
		$this->redirectError(ApiExceptionRenderer::METHOD_NOT_ALLOWED, "methodNotAllowed", $error);
	}

	public function internalError($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "internalError", $error);
	}

	public function notImplemented($error) {
		$this->redirectError(ApiExceptionRenderer::NOT_IMPLEMENTED, "notImplemented", $error);
	}
	
	// --------------------- OTHERS
	public function missingController($error) {
		$this->redirectError(ApiExceptionRenderer::NOT_FOUND, "missingController", $error);
	}

	public function missingAction($error) {
		$this->redirectError(ApiExceptionRenderer::NOT_FOUND, "missingAction", $error);
	}

	public function missingView($error) {
		$this->redirectError(ApiExceptionRenderer::NOT_FOUND, "missingView", $error);
	}

	public function missingLayout($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingLayout", $error);
	}

	public function missingHelper($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingHelper", $error);
	}

	public function missingBehavior($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingBehavior", $error);
	}

	public function missingComponent($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingComponent", $error);
	}

	public function missingTask($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingTask", $error);
	}

	public function missingShell($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingShell", $error);
	}

	public function missingShellMethod($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingShellMethod", $error);
	}

	public function missingDatabase($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingDatabase", $error);
	}

	public function missingConnection($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingConnection", $error);
	}

	public function missingTable($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "missingTable", $error);
	}

	public function privateAction($error) {
		$this->redirectError(ApiExceptionRenderer::INTERNAL_ERROR, "privateAction", $error);
	}
}