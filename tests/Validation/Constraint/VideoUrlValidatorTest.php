<?php declare(strict_types=1);

namespace Tests\Becklyn\VideoPlatforms\Validation\Constraint;

use Becklyn\VideoPlatforms\Validation\Constraint\VideoUrl;
use Becklyn\VideoPlatforms\Validation\Constraint\VideoUrlValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class VideoUrlValidatorTest extends TestCase
{
    /**
     */
    public function provideVariations () : iterable
    {
        yield [null, [], null];
        yield ["", [], null];
        yield [["platform" => "vimeo", "id" => "123"], [], null];
        yield [["invalid" => "yep"], [], "becklyn.video-platforms.invalid"];
        yield [["platform" => "youtube", "id" => "123"], ["vimeo"], "becklyn.video-platforms.unsupported-platform"];
    }


    /**
     * @dataProvider provideVariations
     */
    public function testVariations ($value, array $platforms, ?string $violationMessage) : void
    {
        $context = $this->createContext($violationMessage);
        $constraint = $this->createConstraint($platforms);

        $validator = new VideoUrlValidator();
        $validator->initialize($context);
        $validator->validate($value, $constraint);
    }


    /**
     */
    private function createConstraint (?array $platforms = null) : VideoUrl
    {
        $constraint = new VideoUrl();
        $constraint->platforms = $platforms;

        return $constraint;
    }


    /**
     */
    private function createContext (?string $violationMessage) : ExecutionContextInterface
    {
        $context = $this->getMockBuilder(ExecutionContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $builder = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        if (null === $violationMessage)
        {
            $context
                ->expects(self::never())
                ->method("buildViolation");
        }
        else
        {
            $context
                ->expects(self::once())
                ->method("buildViolation")
                ->with($violationMessage)
                ->willReturn($builder);
        }

        return $context;
    }
}
