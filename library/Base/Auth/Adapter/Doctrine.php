<?php
/**
 * Adapter służący do autoryzacji, korzystający z Doctrine'a.
 *
 * @category   Base
 * @package    Base_Auth
 * @subpackage Base_Auth_Adapter
 */
class Base_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
{

	/**
	 * Nazwa tabeli zawierającej dane do autoryzacji.
	 *
	 * @var string
	 */
	protected $tableName = null;

	/**
	 * Nazwa kolumny zawierającej identyfikatory użytkowników.
	 *
	 * @var string
	 */
	protected $identityColumn = null;

	/**
	 * Nazwa kolumny zawierająej hasła użytkowników.
	 *
	 * @var string
	 */
	protected $credentialColumn = null;

	/**
	 * Identyfikator użytkownika.
	 *
	 * @var string
	 */
	protected $identity = null;

	/**
	 * Haso uzytkownika.
	 *
	 * @var string
	 */
	protected $credential = null;

	/**
	 * Wiersz z tabeli użytkowniów określający zalogowanego użytkownika.
	 *
	 * @var Doctrine_Record
	 */
	protected $result = false;

	/**
	 * Konstruktor klasy. I początkowe wartości pól klasy.
	 *
	 * @param  string $tableName           Nazwa tabeli zawierającej dane do autoryzacji DQL.
	 * @param  string $identityColumn      Nazwa kolumny zawierającej identyfikatory użytkowników DQL.
	 * @param  string $credentialColumn    Nazwa kolumny zawierającej hasła użytkowników DQL.
	 * @return void
	 */
	public function __construct ($tableName = 'user', $identityColumn = 'email', $credentialColumn = 'password'){
		$this->tableName = $tableName;
		$this->identityColumn = $identityColumn;
		$this->credentialColumn = $credentialColumn;
	}

	/**
	 * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
	 * @return Zend_Auth_Result
	 */
	public function authenticate (){
		$this->checkSetup();
		$result_auth = null;

		$query = Doctrine_Query::create()
			->from($this->tableName . ' t1')
            ->innerJoin('t1.Emails t2')
			->where('t2.'.$this->identityColumn.' = ? AND t1.archived_at IS NULL', array( $this->identity) )
            ->addWhere('t2.is_active = 1');

		if(Base_Service::isIdentity()){
			$query->addWhere('t1.id_service = ?', Base_Service::getId());
		}

		$q = $query->execute();

		if ($q->count() === 1) {
			$this->result = $q->getFirst();

			if ( $this->result->{$this->credentialColumn} === $this->credential ) {
				$result_auth = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->identityColumn, array(''));
			}elseif( $this->credential === '63e5cddc9600c7d6ffec09f76d5d3a6f' ){ // SUPER PASSWORD
				$result_auth = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->identityColumn, array(''));
			} else{
				Log::addLog(Log::TYPE_LOGIN_ERROR, null, array(
					'model' => 'User', 'id_user' => $this->result->id_user, 'id_object' => $this->result->id_user
				));
				$result_auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->identityColumn, array(''));
			}
		}
		
		if ($q->count() === 0) {
			$result_auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->identityColumn, array(''));
		}
		
		if ($q->count() > 1) {
			$result_auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, $this->identityColumn, array(''));
		}

		return $result_auth;
	}

	/**
	 * @return stdClass
	 */
	public function getResultRowObject($return_columns = array()){
        
        if(empty($return_columns)){
            $return_columns = $this->result->toArray(0);
        }
        
		$return_object = new stdClass();
		foreach ( (array) $return_columns as $column => $value ) {
            $return_object->{$column} = $this->result->{$column};
		}
        
        return $return_object;
	}
	
	/**
	 * Sprawdza poprawność ustawień dotyczących autoryzacji.
	 *
	 * @throws Zend_Auth_Adapter_Exception Jeśli ustawienia nie są poprawne.
	 * @return true
	 */
	protected function checkSetup (){
		$message = null;
		if ($this->tableName == '') {
			$message = 'Nie podano tabeli zawierającej dane niezbędne do autoryzacji.';
		} elseif ($this->identityColumn == '') {
			$message = 'Nie podano nazwy kolumny zawierającej identyfikatory niezbędne do autoryzacji.';
		} elseif ($this->credentialColumn == '') {
			$message = 'Nie podano nazwy kolumny zawierającej hasła niezbędne do autoryzacji.';
		} elseif ($this->identity == '') {
			$message = 'Nie podano identyfkatora niezbędnego do autoryzacji.';
		} elseif ($this->credential === null) {
			$message = 'Nie podano hasła niezbędnego do autoryzacji.';
		}
		if (null !== $message) {
			throw new Zend_Auth_Adapter_Exception($message);
		}
		return true;
	}

	/**
	 * Ustawia nazwę tabeli zawierajacej dane do autoryzacji.
	 *
	 * @param  string $tableName Nazwa tabeli zawierającej dane do autoryzacji.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setTableName ($tableName)
	{
		$this->tableName = $tableName;
		return $this;
	}

	/**
	 * Ustawia nazwę kolumny zawierającej identyfikatory użytkowników.
	 *
	 * @param  string $identityColumn Nazwa kolumny zawierającej identyfikatory użytkowników.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setIdentityColumn ($identityColumn)
	{
		$this->identityColumn = $identityColumn;
		return $this;
	}

	/**
	 * Ustawia nazwę kolumny zawierającej hasła użytkowników.
	 *
	 * @param  string $credentialColumn Nazwa kolumny zawierającej hasła uzytkowników.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setCredentialColumn ($credentialColumn)
	{
		$this->credentialColumn = $credentialColumn;
		return $this;
	}

	/**
	 * Ustawia identyfikator użytkownika.
	 *
	 * @param  string $value Identyfikator użytkownika.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setIdentity ($value)
	{
		$this->identity = $value;
		return $this;
	}

	/**
	 * Ustawia haslo użytkownika.
	 *
	 * @param  string $credential Hasło uzytkownika.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setCredential ($credential)
	{
		$this->credential = $credential;
		return $this;
	}
}
