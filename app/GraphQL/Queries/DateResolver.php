<?php
namespace App\GraphQL\Queries;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DateResolver
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        #return now()->toDateTimeString();
        return Carbon::now()->toDateTimeString();
    }
}
