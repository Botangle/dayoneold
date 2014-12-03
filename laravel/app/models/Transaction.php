<?php

class Transaction extends MagniloquentContextsPlus {

    const BUY = 'buy';
    CONST SELL = 'sell';
    CONST TRANSFER = 'transfer';

    public $typeNamesWhenCompleted = array(
        self::BUY => 'Bought',
        self::SELL => 'Sold',
        self::TRANSFER => 'Transferred',
    );

    /**
     * Disable timestamps
     * Even though we'd like to use the created_at (set to created field)
     * However, there is no effective way of disabling updated_at completely. If you prevent
     * it from being added by having a setUpdatedAt function that does nothing, updated_at still
     * gets added in later on in Eloquent/Builder::update()
     * @var bool
     */
    public $timestamps = false;

    /**
     * A cached user total amount so we don't repeatedly query for a total in the same response
     * @var decimal
     */
    private $_cachedUserTotalAmount;

    /**
     * A cached user total amount in last 24 hours so we don't repeatedly query for a total in the same response
     * @var decimal
     */
    private $_cachedUser24HoursTotal;

    /**
     * TransactionHandler may add payment errors to this array
     * @var array
     */
    public $validationErrors = [];

    protected $niceNames = [
        'userTotal' => 'sum of all transactions',
        'user24HoursTotal'  => 'number of credits sold in a 24 hour period',
        'userCreditAmount'   => 'recorded credit amount',
    ];

    /**
     * What POST values we'll even take with massive assignment
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'amount',
        'type',
        'transaction_key',
        'lesson_id',
        'created',
        'nonce',
        'paypal_email_address',
        'userTotal',
        'user24HoursTotal',
        'userCreditAmount',
    );

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
            'user_id'                   => array('required', 'exists:users,id'),
            'type'                      => array('in:buy,sell,transfer'),
            'transaction_key'           => array('alpha_num'),
        ),
        'create'    => array(),
        'update'    => array(),
    );

    /**
     * User relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * Lesson relationship
     * Note: not every transaction is related to a lesson
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo('Lesson');
    }

    protected function addConditionalRules($validator)
    {
        // If type is transfer, lesson_id is required and must exist
        // Replaces validateLessonIdIfATransaction from old system
        //  (which was wrongly only working if type == transaction that never happened)
        $validator->sometimes('lesson_id', 'required|exists:lessons,id', function($input)
            {
                return $input->type == 'transfer';
            });

        // Replaces ValidateIntegerIfABuy from old system
        $validator->sometimes('amount', 'required|integer', function($input)
            {
                return $input->type == 'buy';
            });

        // Replaces ValidateDecimalIfNotABuy from old system
        $validator->sometimes('amount', 'required|numeric', function($input)
            {
                return $input->type != 'buy';
            });

        // Replaces validateThatUserDoesntHaveANegativeBalance from old system
        $validator->sometimes('userTotal', 'required|min:0.0001', function($input)
            {
                return $input->type == 'sell';
            });

        // Replaces validateUserHasThatAmountToSell from old system
        //  Essentially, user credits should equal the userTotal
        $validator->sometimes('userTotal', 'required|same:userCreditAmount', function($input)
            {
                return $input->type == 'sell';
            });

        // Replaces validateMaxOneHundredCreditsSoldPer24Hrs from old system
        //  Essentially, the user can't sell more than 100 units per 24 hour period
        $validator->sometimes('user24HoursTotal', 'required|max:100', function($input)
            {
                return $input->type == 'sell';
            });

        // Replaces validateEmailAddressForSell from old system
        $validator->sometimes('paypal_email_address', 'required|email', function($input)
            {
                return $input->type == 'sell';
            });
    }

    /**
     * Scopes the transactions for a particular user
     *
     * @param $query
     * @param User $user
     */
    public function scopeForUser($query, User $user)
    {
        $query->where('user_id', $user->id);
    }

    /**
     * Scopes the transactions to those added in the last 24 hours
     * @param $query
     */
    public function scopeLast24Hours($query)
    {
        $query->whereRaw('created > DATE_SUB(NOW(), INTERVAL 24 HOUR)');
    }

    /**
     * Returns user total based on the transactions table amounts
     * for the current transaction's user
     *
     * @return int|decimal
     */
    function getUserTotalAttribute()
    {
        if (!$this->_cachedUserTotalAmount){
            $this->_cachedUserTotalAmount = Transaction::forUser($this->user)->sum('amount');
        }
        return $this->_cachedUserTotalAmount;
    }

    /**
     * Returns user total based on the transactions table amounts for the last 24 hours
     * for the current transaction's user (including the current transaction)
     *
     * @return int|decimal
     */
    function getUser24HoursTotalAttribute()
    {
        if (!$this->_cachedUser24HoursTotal){
            $this->_cachedUser24HoursTotal = Transaction::forUser($this->user)->last24Hours()->sum('amount')
                                            + $this->amount;
        }
        return $this->_cachedUser24HoursTotal;
    }

    public function addBuy()
    {
        // enforce that this is a buy
        $this->type = 'buy';

        return $this->addTransaction();
    }

    public function addSell()
    {
        // change the sign on the transaction amount when we sell
        $this->amount = 0 - $this->amount;

        // enforce that this is a sell
        $this->type = 'sell';

        return $this->addTransaction();
    }

    public function addTransfer()
    {
        // enforce that this is a transfer
        $this->type = 'transfer';

        return $this->addTransaction();
    }


    private function addTransaction()
    {
        // run everything in a DB transaction to handle rolling changes back if we have payment or other issues
        DB::beginTransaction();

        try{
            if (!$this->save()){
                DB::rollback();
                Log::error('Failed to save new transaction:' . $this->toJson());
                return false;
            }
            // now handle updating our user credit denormalized field info
            if (!$this->updateUserCredit()){
                DB::rollback();
                Log::error('Failed to update user credit for added transaction:' . $this->toJson());
                return false;
            }
            DB::commit();
            return true;

        } catch (Exception $e){
            DB::rollback();
            Log::error("Transaction::addTransaction failed with error ". $e->getMessage(). "\nTransaction data: " . $this->toJson());
        }
        return false;
    }

    protected function updateUserCredit()
    {
        if ($this->user->credit){
            $userCredit = $this->user->credit;
            $userCredit->amount = Transaction::forUser($this->user)->sum('amount');
        } else {
            $userCredit = new UserCredit;
            $userCredit->fill([
                    'user_id'   => $this->user_id,
                    'amount'    => Transaction::forUser($this->user)->sum('amount'),
                ]);
        }
        return $userCredit->save();
    }

    public static function charge(LessonPayment $lessonPayment)
    {
        // run everything in a DB transaction to handle rolling changes back if we have payment or other issues
        DB::beginTransaction();

        try{
            $studentTransaction = new Transaction;
            $studentTransaction->fill([
                    'user_id'   => $lessonPayment->student_id,
                    'amount'    => (0 - $lessonPayment->payment_amount),
                    'lesson_id' => $lessonPayment->lesson_id,
                    'type'      => 'transfer',
                    'transaction_key'   => '0',
                ]);
            // Save student transaction and update userCredit
            if (!$studentTransaction->addTransaction()){
                DB::rollback();
                Log::error("Failed to save student transaction.");
                return false;
            }
            // The transaction_key isn't really important in this case
            // but the id value is more reassuring to users than a 0.
            $studentTransaction->transaction_key = $studentTransaction->id;
            $studentTransaction->save();

            $expertTransaction = new Transaction;
            $expertTransaction->fill([
                    'user_id'           => $lessonPayment->tutor_id,
                    'amount'            => ($lessonPayment->payment_amount - $lessonPayment->fee),
                    'lesson_id'         => $lessonPayment->lesson_id,
                    'type'              => 'transfer',
                    'transaction_key'   => $studentTransaction->id,
                ]);
            // Save expert transaction and update userCredit
            if (!$expertTransaction->addTransaction()){
                DB::rollback();
                Log::error("Failed to save expert transaction.");
                return false;
            }

            DB::commit();
            return $studentTransaction;

        } catch (Exception $e){
            DB::rollback();
            Log::error("Transaction::charge failed with error ". $e->getMessage());
        }
        return false;

    }

    /**
     * @param array $new_attributes
     * @param bool $forceSave
     * @return bool
     */
    public function save(array $new_attributes = array(), $forceSave = false)
    {
        try {
            if($this->type == 'buy') {
                Event::fire('transaction.purchase', array($this));
                // Nonce isn't actually a table column, so we need to remove that from attributes before saving
                unset($this->attributes['nonce']);

            } elseif($this->type == 'sell') {
                Event::fire('transaction.sale', array($this));
                // paypal_email_address isn't actually a table column, so we need to remove that
                //  from attributes before saving
                unset($this->attributes['paypal_email_address']);

            }
        } catch(Exception $e){
            Log::error('Transaction::save failed: '. $e->getMessage() .' Trace: '. $e->getTraceAsString());
            return false;
        }
        if ($this->errors()->count() > 0){
            return false;
        }
        return parent::save();
    }

    public function getTypeDisplayAttribute()
    {
        return $this->typeNamesWhenCompleted[$this->type];
    }

    public function getUserCreditAmountAttribute()
    {
        return $this->user->creditAmount;
    }

    public static function generateBraintreeToken()
    {
        // sandbox or production
        Braintree_Configuration::environment(Config::get('services.braintree.mode'));

        // set other items as well
        Braintree_Configuration::merchantId(Config::get('services.braintree.merchantId'));
        Braintree_Configuration::publicKey(Config::get('services.braintree.publicKey'));

        // set the Braintree private key
        $key = Config::get('services.braintree.privateKey');
        if (!$key) {
            throw new Exception('Braintree private key is not set.');
        }
        Braintree_Configuration::privateKey($key);
        return Braintree_ClientToken::generate();
    }

    public function removeAttribute($attributeName)
    {
        if(isset($this->attributes[$attributeName])){
            unset($this->attributes[$attributeName]);
        }
    }

}
