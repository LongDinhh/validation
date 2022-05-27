<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Closure;
use Doctrine\DBAL\Connection;
use Coccoc\Validation\Rule;

/**
 * Class Unique
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Unique
 */
class Unique extends Rule
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    protected $message = 'rule.unique';

    /**
     * @var array
     */
    protected $fillableParams = ['table', 'column', 'ignore', 'ignore_column'];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function table(string $table): self
    {
        $this->params['table'] = $table;

        return $this;
    }

    public function column(string $column): self
    {
        $this->params['column'] = $column;

        return $this;
    }

    public function ignore($value, string $column = null): self
    {
        $this->params['ignore'] = $value;
        $this->params['ignore_column'] = $column;

        return $this;
    }

    public function where(Closure $callback): self
    {
        $this->params['callback'] = $callback;

        return $this;
    }

    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['table', 'column']);

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('COUNT(*) AS cnt')
            ->from($this->parameter('table'))
            ->where($qb->expr()->eq($this->parameter('column'), ':value'))
            ->setParameter('value', $value);

        if ($this->parameter('ignore')) {
            $qb
                ->andWhere($qb->expr()->neq($this->parameter('ignore_column') ?? $this->parameter('column'), ':ignore'))
                ->setParameter('ignore', $this->parameter('ignore'));
        }

        if (null !== $func = $this->parameter('callback')) {
            $func($qb);
        }

        return 0 === (int)$qb->executeQuery()->fetchOne();
    }
}
