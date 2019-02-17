<?php
/**
 * Adapter służący do autoryzacji
 *
 * @category   Base
 * @package    Base_Auth
 * @subpackage Base_Auth_Adapter
 */
class Base_Auth_Adapter_DbTable implements Zend_Auth_Adapter_Interface
{


    protected $dbAdapter = null;


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
	 * Nazwa kolumny zawierającej hasła użytkowników.
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
	 * Hasło użytkownika.
	 *
	 * @var string
	 */
	protected $credential = null;

	/**
	 * Wiersz z tabeli użytkowników określajcy zalogowanego użytkownika.
	 * @var User
	 */
	protected $result = null;

	/**
	 * Konstruktor klasy. I początkowe wartości pól klasy.
	 *
	 * @param  Zend_Db_Adapter_Abstract $dbAdapter
	 * @param  string $tableName           Nazwa tabeli zawierającej dane do autoryzacji DQL.
	 * @param  string $identityColumn      Nazwa kolumny zawierającej identyfikatory u�ytkownik�w DQL.
	 * @param  string $credentialColumn    Nazwa kolumny zawierającej hasła u�ytkownik�w DQL.
	 * @return void
	 */
	public function __construct ($dbAdapter = null, $tableName = 'user', $identityColumn = 'email', $credentialColumn = 'pass' ){
		$this->dbAdapter = $dbAdapter;
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

        $select = $this->dbAdapter->select();
        $select->from( $this->tableName )
                ->where( "$this->identityColumn = ?", $this->identity )
                ->where( "is_active = 1" );

        $q = $this->dbAdapter->fetchAll( $select );

		if ( 1 === count( $q ) )
        {
			$this->result = $q[0];
            $this->credential = md5( $this->result['hash'].$this->credential );

			if ( $this->result[$this->credentialColumn] === $this->credential ) {
				$result_auth = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->identityColumn, array(''));
			}else{
				$result_auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->identityColumn, array(''));
			}

		}

		if ( 0 === count( $q ) )
        {
			$result_auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->identityColumn, array(''));
		}

		if ( 1 < count( $q ) )
        {
			$result_auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, $this->identityColumn, array(''));
		}

		return $result_auth;
	}

	/**
	 * @param  string|array $returnColumns
	 * @param  string|array $omitColumns
	 * @return User
	 */
	public function getResultRowObject(){
		$return_object = new stdClass();
		foreach( $this->result as $column => $value ){
			$return_object->{$column} = $this->result[$column];
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
	 * Ustawia nazw� tabeli zawieraj�cej dane do autoryzacji.
	 *
	 * @param  string $tableName Nazwa tabeli zawieraj�cej dane do autoryzacji.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setTableName ($tableName)
	{
		$this->tableName = $tableName;
		return $this;
	}

	/**
	 * Ustawia nazw� kolumny zawieraj�cej identyfikatory u�ytkownik�w.
	 *
	 * @param  string $identityColumn Nazwa kolumny zawieraj�cej identyfikatory u�ytkownik�w.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setIdentityColumn ($identityColumn)
	{
		$this->identityColumn = $identityColumn;
		return $this;
	}

	/**
	 * Ustawia nazw� kolumny zawieraj�cej has�a u�ytkownik�w.
	 *
	 * @param  string $credentialColumn Nazwa kolumny zawieraj�cej has�a u�ytkownik�w.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setCredentialColumn ($credentialColumn)
	{
		$this->credentialColumn = $credentialColumn;
		return $this;
	}

	/**
	 * Ustawia identyfikator u�ytkownika.
	 *
	 * @param  string $value Identyfikator u�ytkownika.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setIdentity ($value)
	{
		$this->identity = $value;
		return $this;
	}

	/**
	 * Ustawia has�o u�ytkownika.
	 *
	 * @param  string $credential Has�o u�ytkownika.
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function setCredential ($credential)
	{
		$this->credential = $credential;
		return $this;
	}
}
