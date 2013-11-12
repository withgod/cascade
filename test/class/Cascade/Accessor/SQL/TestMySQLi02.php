<?php
/**
 *  TestMySQLi02.php
 *
 *  << DataFormatの条件 >>
 *    trable_name : test_cascade
 *    primary_key : id
 *    fetch_key   : user_id
 *    fetch_mode  : NUM
 *
 *  @author   Yoshinobu Kinugasa <kinugasa@gree.co.jp>
 *  @package  Cascade
 *  @version  $Id:$
 */

/**
 *  Cascade_Accessor_SQL_TestMySQLi02
 */
final class Cascade_Accessor_SQL_TestMySQLi02
    extends PHPUnit_Framework_TestCase
{
    // ----[ Class Constants ]----------------------------------------
    const SCHEMA_NAME       = 'test#Accessor_SQL_TestMySQLi02';

    // ----[ Class Constants ]----------------------------------------
    const FIELD_NUM_ID      = 0;
    const FIELD_NUM_USER_ID = 1;
    const FIELD_NUM_ITEM_ID = 2;
    const FIELD_NUM_NUM     = 3;

    // ----[ Methods ]------------------------------------------------
    // {{{ setUp
    /**
     *  初期化処理
     */
    public /* void */
        function setUp(/* void */)
    {
        if (!extension_loaded("mysqli")) {
            $this->markTestSkipped("Can't use mysqli extension. skip test");
        }
        $this->provider();
    }
    // }}}
    // ----[ Methods ]------------------------------------------------
    // {{{ test_get_01
    public /* void */
        function test_get_01(/* void */)
    {
        $user_id  = 105;
        $accessor = Cascade::getAccessor(self::SCHEMA_NAME);
        $data_01  = $accessor->get($user_id, $hint = NULL, $use_master = TRUE);
        $data_02  = $accessor->get($user_id, $hint = NULL, $use_master = FALSE);
        $data_03  = $accessor->getEx('standby', $user_id, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertInternalType('array', $data_01[0]);
        $this->assertInternalType('array', $data_01[1]);
        $this->assertEquals(2, count($data_01));
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);

        // -----------------------------
        $this->assertEquals(9,   $data_01[0][self::FIELD_NUM_ID]);
        $this->assertEquals(105, $data_01[0][self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $data_01[0][self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $data_01[0][self::FIELD_NUM_NUM]);

        // -----------------------------
        $this->assertEquals(10,  $data_01[1][self::FIELD_NUM_ID]);
        $this->assertEquals(105, $data_01[1][self::FIELD_NUM_USER_ID]);
        $this->assertEquals(5,   $data_01[1][self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(4,   $data_01[1][self::FIELD_NUM_NUM]);
    }
    // }}}
    // {{{ test_mget_01
    public function test_mget_01()
    {
        $user_ids = array(102, 105);
        $accessor = Cascade::getAccessor(self::SCHEMA_NAME);
        $data_01  = $accessor->mget($user_ids, $hint = NULL, $use_master = TRUE);
        $data_02  = $accessor->mget($user_ids, $hint = NULL, $use_master = FALSE);
        $data_03  = $accessor->mgetEx('standby', $user_ids, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertInternalType('array', $data_01[0]);
        $this->assertInternalType('array', $data_01[1]);

        $this->assertEquals(2, count($data_01));
        $this->assertEquals(1, count($data_01[0]));
        $this->assertEquals(2, count($data_01[1]));

        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);

        // -----------------------------
        $this->assertEquals(6,   $data_01[0][0][self::FIELD_NUM_ID]);
        $this->assertEquals(102, $data_01[0][0][self::FIELD_NUM_USER_ID]);
        $this->assertEquals(2,   $data_01[0][0][self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(1,   $data_01[0][0][self::FIELD_NUM_NUM]);

        // -----------------------------
        $this->assertEquals(9,   $data_01[1][0][self::FIELD_NUM_ID]);
        $this->assertEquals(105, $data_01[1][0][self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $data_01[1][0][self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $data_01[1][0][self::FIELD_NUM_NUM]);

        // -----------------------------
        $this->assertEquals(10,  $data_01[1][1][self::FIELD_NUM_ID]);
        $this->assertEquals(105, $data_01[1][1][self::FIELD_NUM_USER_ID]);
        $this->assertEquals(5,   $data_01[1][1][self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(4,   $data_01[1][1][self::FIELD_NUM_NUM]);
    }
    // }}}
    // {{{ test_find_first_01
    public function test_find_first_01()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'find_by_gt_num';
        $params    = array('num' => 3, 'except_user_id' => 100);
        $data_01   = $accessor->findFirst($stmt_name, $params, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->findFirst($stmt_name, $params, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->findFirstEx('standby', $stmt_name, $params, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);

        // -----------------------------
        $this->assertEquals(5,   $data_01[self::FIELD_NUM_ID]);
        $this->assertEquals(101, $data_01[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $data_01[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $data_01[self::FIELD_NUM_NUM]);
    }
    // }}}
    // {{{ test_find_01
    public function test_find_01()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'find_by_gt_num';
        $params    = array('num' => 3, 'except_user_id' => 100);
        $data_01   = $accessor->find($stmt_name, $params, $offset = NULL, $limit = NULL, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->find($stmt_name, $params, $offset = NULL, $limit = NULL, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->findEx('standby', $stmt_name, $params, $offset = NULL, $limit = NULL, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertInternalType('array', $data_01[0]);
        $this->assertInternalType('array', $data_01[1]);
        $this->assertInternalType('array', $data_01[2]);
        $this->assertEquals(3, count($data_01));
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(5,   $row[self::FIELD_NUM_ID]);
        $this->assertEquals(101, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $row[self::FIELD_NUM_NUM]);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(9,   $row[self::FIELD_NUM_ID]);
        $this->assertEquals(105, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $row[self::FIELD_NUM_NUM]);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(10,  $row[self::FIELD_NUM_ID]);
        $this->assertEquals(105, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(5,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(4,   $row[self::FIELD_NUM_NUM]);
    }
    // }}}
    // {{{ test_find_02
    public function test_find_02()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'find_by_gt_num';
        $params    = array('num' => 3, 'except_user_id' => 100);
        $data_01   = $accessor->find($stmt_name, $params, $offset = NULL, $limit = 2, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->find($stmt_name, $params, $offset = NULL, $limit = 2, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->findEx('standby', $stmt_name, $params, $offset = NULL, $limit = 2, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertInternalType('array', $data_01[0]);
        $this->assertInternalType('array', $data_01[1]);
        $this->assertEquals(2, count($data_01));
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(5,   $row[self::FIELD_NUM_ID]);
        $this->assertEquals(101, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $row[self::FIELD_NUM_NUM]);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(9,   $row[self::FIELD_NUM_ID]);
        $this->assertEquals(105, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $row[self::FIELD_NUM_NUM]);
    }
    // }}}
    // {{{ test_find_03
    public function test_find_03()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'find_by_gt_num';
        $params    = array('num' => 3, 'except_user_id' => 100);
        $data_01   = $accessor->find($stmt_name, $params, $offset = 1, $limit = 2, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->find($stmt_name, $params, $offset = 1, $limit = 2, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->findEx('standby', $stmt_name, $params, $offset = 1, $limit = 2, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertInternalType('array', $data_01[0]);
        $this->assertInternalType('array', $data_01[1]);
        $this->assertEquals(2, count($data_01));
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(9,   $row[self::FIELD_NUM_ID]);
        $this->assertEquals(105, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(1,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(3,   $row[self::FIELD_NUM_NUM]);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(10,  $row[self::FIELD_NUM_ID]);
        $this->assertEquals(105, $row[self::FIELD_NUM_USER_ID]);
        $this->assertEquals(5,   $row[self::FIELD_NUM_ITEM_ID]);
        $this->assertEquals(4,   $row[self::FIELD_NUM_NUM]);
    }
    // }}}
    // {{{ test_find_04
    public function test_find_04()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'find_by_gt_num';
        $params    = array('num' => 3, 'except_user_id' => 100);
        $data_01   = $accessor->find($stmt_name, $params, $offset = 10, $limit = 2, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->find($stmt_name, $params, $offset = 10, $limit = 2, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->findEx('standby', $stmt_name, $params, $offset = 10, $limit = 2, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertEquals(0, count($data_01));
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);
    }
    // }}}
    // {{{ test_find_05
    public function test_find_05()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'find_user_by_gt_num';
        $params    = array('num' => 3, 'except_user_id' => 100);
        $data_01   = $accessor->find($stmt_name, $params, $offset = 1, $limit = 2, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->find($stmt_name, $params, $offset = 1, $limit = 2, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->findEx('standby', $stmt_name, $params, $offset = 1, $limit = 2, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertInternalType('array', $data_01);
        $this->assertInternalType('array', $data_01[0]);
        $this->assertInternalType('array', $data_01[1]);
        $this->assertEquals(2, count($data_01));
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(105, $row[0]);
        $this->assertEquals(1,   $row[1]);

        // -----------------------------
        $row = array_shift($data_01);
        $this->assertEquals(105, $row[0]);
        $this->assertEquals(5,   $row[1]);
    }
    // }}}
    // {{{ test_to_value_01
    public function test_to_value_01()
    {
        $accessor   = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'sum_num';
        $params    = array('user_id' => 100);
        $data_01   = $accessor->toValue($stmt_name, $params, $hint = NULL, $use_master = TRUE);
        $data_02   = $accessor->toValue($stmt_name, $params, $hint = NULL, $use_master = FALSE);
        $data_03   = $accessor->toValueEx('standby', $stmt_name, $params, $hint = NULL, $use_master = FALSE);

        // -----------------------------
        $this->assertEquals($data_01, $data_02);
        $this->assertEquals($data_01, $data_03);
        $this->assertEquals(11, $data_01);
    }
    // }}}
    // {{{ test_to_execute_01
    public function test_to_execute_01()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'inc_num';
        $params    = array('user_id' => 100, 'item_id' => 3, 'diff_num' => 1);
        $data_01   = $accessor->execute($stmt_name, $params, $hint = NULL);
        $stmt_name = '__inc_num__';
        $data_02   = $accessor->findFirst($stmt_name, $params = NULL, $hint = NULL, $use_master = TRUE);

        // -----------------------------
        $this->assertEquals(2, $data_01);
        $this->assertEquals(3, $data_02[0]);
        $this->assertEquals(4, $data_02[1]);
    }
    // }}}
    // {{{ test_to_execute_02
    public function test_to_execute_02()
    {
        $accessor  = Cascade::getAccessor(self::SCHEMA_NAME);
        $stmt_name = 'inc_num';
        $params    = array('user_id' => 200, 'item_id' => 5, 'diff_num' => 3);
        $data_01   = $accessor->execute($stmt_name, $params, $hint = NULL);
        $stmt_name = 'sum_num';
        $params    = array('user_id' => 200);
        $data_02   = $accessor->toValue($stmt_name, $params, $hint = NULL, $use_master = TRUE);

        // -----------------------------
        $this->assertEquals(1, $data_01);
        $this->assertEquals(3, $data_02);
    }
    // }}}
    // ----[ Methods ]------------------------------------------------
    // {{{ provider
    /**
     *  テストデータを作る
     */
    protected /* void */
        function provider(/* void */)
    {
        $mysqli = new Cascade_Driver_SQL_MySQLi('gree(mysql)://master/test');
        // テストデータを作る
        // SINGLE-COLUMN-INDEX
        $mysqli->query('DROP TABLE IF EXISTS test_cascade');
        $mysqli->query($this->get_table_schema());
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 100, 1, 1, now(), now())'); // @id=1
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 100, 2, 2, now(), now())'); // @id=2
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 100, 3, 3, now(), now())'); // @id=3
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 100, 4, 5, now(), now())'); // @id=4
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 101, 1, 3, now(), now())'); // @id=5
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 102, 2, 1, now(), now())'); // @id=6
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 103, 3, 1, now(), now())'); // @id=7
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 104, 4, 1, now(), now())'); // @id=8
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 105, 1, 3, now(), now())'); // @id=9
        $mysqli->query('INSERT INTO test_cascade VALUE(default, 105, 5, 4, now(), now())'); // @id=10
    }
    // }}}
    // {{{ get_table_schema
    /**
     *  サンプルデータのテーブルスキーマを取得する
     *
     *  @return  string  テーブル作成クエリ
     */
    protected /* string */
        function get_table_schema(/* void */)
    {
        return <<< EOD
CREATE TABLE IF NOT EXISTS test_cascade(
   id         INT      UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
   user_id    INT      UNSIGNED  NOT NULL,
   item_id    SMALLINT UNSIGNED  NOT NULL,
   num        INT                NOT NULL DEFAULT 0,
   mtime      TIMESTAMP          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   ctime      DATETIME           NOT NULL DEFAULT '0000-00-00 00:00:00',
   UNIQUE KEY ukey_01(user_id, item_id),
   KEY         key_01(user_id, num)
) ENGINE=InnoDB
EOD;
    }
    // }}}
};

/**
 *  データーフォーマット
 */
final class Cascade_Accessor_SQL_TestMySQLi02_DataFormat
    extends Cascade_DB_SQL_DataFormat
{
    // ----[ Properties ]---------------------------------------------
    protected $table_name        = 'test_cascade';
    // @var mixed   PRIMARY-KEY   (multi-column-indexは配列で定義)
    protected $primary_key       = 'id';
    // @var mixed   データ取得KEY (NULLの場合:primary_keyを採用)
    protected $fetch_key         = 'user_id';
    // @var boolean AUTO_INCREMENTフラグ
    protected $auto_increment    = TRUE;
    // @var string  フィールド名 (更新日)
    protected $updated_at_column = 'mtime';
    // @var string  フィールド名 (作成日)
    protected $created_at_column = 'ctime';
    // @var int    DB接続ドライバー種別
    protected $driver_type       = self::DRIVER_MYSQLI;
    // @var int     結果のフェッチモード
    protected $fetch_mode        = self::FETCH_MODE_NUM;
    // @var string  マスターDSN
    protected $master_dsn        = 'gree://master/test';
    // @var string  スレーブDSN
    protected $slave_dsn         = 'gree://slave/test';
    // @var array   拡張DSNリスト
    protected $extra_dsn         = array(
        'standby' => 'gree://standby/test',
    );
    // @var array   フィールド名リスト
    protected $field_names       = array(
        'id',
        'user_id',
        'item_id',
        'num',
        'mtime',
        'ctime',
    );
    // @var array   クエリ定義
    protected $queries = array(
        'find_by_gt_num' => array(
            'sql' => 'SELECT * FROM __TABLE_NAME__ WHERE :num <= num AND user_id != :except_user_id ORDER BY user_id, item_id',
        ),
        'find_user_by_gt_num' => array(
            'sql' => 'SELECT user_id as user, item_id as item FROM __TABLE_NAME__ WHERE :num <= num AND user_id != :except_user_id ORDER BY user_id, item_id',
        ),
        'sum_num' => array(
            'sql' => 'SELECT SUM(num) FROM __TABLE_NAME__ WHERE user_id = :user_id',
        ),
        'inc_num' => array(
            'sql' => 'INSERT INTO __TABLE_NAME__(user_id, item_id, num, ctime)
                        VALUES(:user_id, :item_id, :diff_num, NOW()) ON DUPLICATE KEY UPDATE
                        num = @after_num := ((@before_num := num) + :diff_num)',
         ),
        '__inc_num__' => array(
            'sql' => 'SELECT @before_num, @after_num',
        ),
    );
};
