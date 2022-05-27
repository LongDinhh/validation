<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Closure;
use Doctrine\DBAL\Connection;
use Coccoc\Validation\Rule;

/**
 * Class Exists
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Exists
 */
class Exists extends Rule
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    protected $message = 'rule.exists';

    /**
     * @var array
     */
    protected $fillableParams = ['table', 'column'];

    /**
     * Exists constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function table(string $table): self
    {
        $this->params['table'] = $table;

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function column(string $column): self
    {
        $this->params['column'] = $column;

        return $this;
    }

    /**
     * @param Closure $callback
     * @return $this
     */
    public function where(Closure $callback): self
    {
        $this->params['callback'] = $callback;

        return $this;
    }

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['table', 'column']);

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('1')
            ->from($this->parameter('table'))
            ->where($qb->expr()->eq($this->parameter('column'), ':value'))
            ->setParameter('value', $value);

        if (null !== $func = $this->parameter('callback')) {
            $func($qb);
        }

        return 1 === (int)$qb->fetchOne();
    }
}
