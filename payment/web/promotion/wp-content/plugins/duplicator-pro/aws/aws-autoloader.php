<?php
defined("ABSPATH") or die("");
if (!class_exists('dup_pro_aws_autoload'))
{
	$GLOBALS['dup_pro_aws_mapping'] = array(
		'DuplicatorPro\Aws\Common\Aws' => __DIR__ . '/Aws/Common/Aws.php',
		'DuplicatorPro\Aws\Common\Client\AbstractClient' => __DIR__ . '/Aws/Common/Client/AbstractClient.php',
		'DuplicatorPro\Aws\Common\Client\AwsClientInterface' => __DIR__ . '/Aws/Common/Client/AwsClientInterface.php',
		'DuplicatorPro\Aws\Common\Client\ClientBuilder' => __DIR__ . '/Aws/Common/Client/ClientBuilder.php',
		'DuplicatorPro\Aws\Common\Client\DefaultClient' => __DIR__ . '/Aws/Common/Client/DefaultClient.php',
		'DuplicatorPro\Aws\Common\Client\ExpiredCredentialsChecker' => __DIR__ . '/Aws/Common/Client/ExpiredCredentialsChecker.php',
		'DuplicatorPro\Aws\Common\Client\ThrottlingErrorChecker' => __DIR__ . '/Aws/Common/Client/ThrottlingErrorChecker.php',
		'DuplicatorPro\Aws\Common\Client\UploadBodyListener' => __DIR__ . '/Aws/Common/Client/UploadBodyListener.php',
		'DuplicatorPro\Aws\Common\Client\UserAgentListener' => __DIR__ . '/Aws/Common/Client/UserAgentListener.php',
		'DuplicatorPro\Aws\Common\Command\AwsQueryVisitor' => __DIR__ . '/Aws/Common/Command/AwsQueryVisitor.php',
		'DuplicatorPro\Aws\Common\Command\JsonCommand' => __DIR__ . '/Aws/Common/Command/JsonCommand.php',
		'DuplicatorPro\Aws\Common\Command\QueryCommand' => __DIR__ . '/Aws/Common/Command/QueryCommand.php',
		'DuplicatorPro\Aws\Common\Command\XmlResponseLocationVisitor' => __DIR__ . '/Aws/Common/Command/XmlResponseLocationVisitor.php',
		'DuplicatorPro\Aws\Common\Credentials\AbstractCredentialsDecorator' => __DIR__ . '/Aws/Common/Credentials/AbstractCredentialsDecorator.php',
		'DuplicatorPro\Aws\Common\Credentials\AbstractRefreshableCredentials' => __DIR__ . '/Aws/Common/Credentials/AbstractRefreshableCredentials.php',
		'DuplicatorPro\Aws\Common\Credentials\CacheableCredentials' => __DIR__ . '/Aws/Common/Credentials/CacheableCredentials.php',
		'DuplicatorPro\Aws\Common\Credentials\Credentials' => __DIR__ . '/Aws/Common/Credentials/Credentials.php',
		'DuplicatorPro\Aws\Common\Credentials\CredentialsInterface' => __DIR__ . '/Aws/Common/Credentials/CredentialsInterface.php',
		'DuplicatorPro\Aws\Common\Credentials\NullCredentials' => __DIR__ . '/Aws/Common/Credentials/NullCredentials.php',
		'DuplicatorPro\Aws\Common\Credentials\RefreshableInstanceProfileCredentials' => __DIR__ . '/Aws/Common/Credentials/RefreshableInstanceProfileCredentials.php',
		'DuplicatorPro\Aws\Common\Enum\ClientOptions' => __DIR__ . '/Aws/Common/Enum/ClientOptions.php',
		'DuplicatorPro\Aws\Common\Enum\DateFormat' => __DIR__ . '/Aws/Common/Enum/DateFormat.php',
		'DuplicatorPro\Aws\Common\Enum\Region' => __DIR__ . '/Aws/Common/Enum/Region.php',
		'DuplicatorPro\Aws\Common\Enum\Size' => __DIR__ . '/Aws/Common/Enum/Size.php',
		'DuplicatorPro\Aws\Common\Enum\Time' => __DIR__ . '/Aws/Common/Enum/Time.php',
		'DuplicatorPro\Aws\Common\Enum\UaString' => __DIR__ . '/Aws/Common/Enum/UaString.php',
		'DuplicatorPro\Aws\Common\Enum' => __DIR__ . '/Aws/Common/Enum.php',
		'DuplicatorPro\Aws\Common\Exception\AwsExceptionInterface' => __DIR__ . '/Aws/Common/Exception/AwsExceptionInterface.php',
		'DuplicatorPro\Aws\Common\Exception\BadMethodCallException' => __DIR__ . '/Aws/Common/Exception/BadMethodCallException.php',
		'DuplicatorPro\Aws\Common\Exception\DomainException' => __DIR__ . '/Aws/Common/Exception/DomainException.php',
		'DuplicatorPro\Aws\Common\Exception\ExceptionFactoryInterface' => __DIR__ . '/Aws/Common/Exception/ExceptionFactoryInterface.php',
		'DuplicatorPro\Aws\Common\Exception\ExceptionListener' => __DIR__ . '/Aws/Common/Exception/ExceptionListener.php',
		'DuplicatorPro\Aws\Common\Exception\InstanceProfileCredentialsException' => __DIR__ . '/Aws/Common/Exception/InstanceProfileCredentialsException.php',
		'DuplicatorPro\Aws\Common\Exception\InvalidArgumentException' => __DIR__ . '/Aws/Common/Exception/InvalidArgumentException.php',
		'DuplicatorPro\Aws\Common\Exception\LogicException' => __DIR__ . '/Aws/Common/Exception/LogicException.php',
		'DuplicatorPro\Aws\Common\Exception\MultipartUploadException' => __DIR__ . '/Aws/Common/Exception/MultipartUploadException.php',
		'DuplicatorPro\Aws\Common\Exception\NamespaceExceptionFactory' => __DIR__ . '/Aws/Common/Exception/NamespaceExceptionFactory.php',
		'DuplicatorPro\Aws\Common\Exception\OutOfBoundsException' => __DIR__ . '/Aws/Common/Exception/OutOfBoundsException.php',
		'DuplicatorPro\Aws\Common\Exception\OverflowException' => __DIR__ . '/Aws/Common/Exception/OverflowException.php',
		'DuplicatorPro\Aws\Common\Exception\Parser\AbstractJsonExceptionParser' => __DIR__ . '/Aws/Common/Exception/Parser/AbstractJsonExceptionParser.php',
		'DuplicatorPro\Aws\Common\Exception\Parser\DefaultXmlExceptionParser' => __DIR__ . '/Aws/Common/Exception/Parser/DefaultXmlExceptionParser.php',
		'DuplicatorPro\Aws\Common\Exception\Parser\ExceptionParserInterface' => __DIR__ . '/Aws/Common/Exception/Parser/ExceptionParserInterface.php',
		'DuplicatorPro\Aws\Common\Exception\Parser\JsonQueryExceptionParser' => __DIR__ . '/Aws/Common/Exception/Parser/JsonQueryExceptionParser.php',
		'DuplicatorPro\Aws\Common\Exception\Parser\JsonRestExceptionParser' => __DIR__ . '/Aws/Common/Exception/Parser/JsonRestExceptionParser.php',
		'DuplicatorPro\Aws\Common\Exception\RequiredExtensionNotLoadedException' => __DIR__ . '/Aws/Common/Exception/RequiredExtensionNotLoadedException.php',
		'DuplicatorPro\Aws\Common\Exception\RuntimeException' => __DIR__ . '/Aws/Common/Exception/RuntimeException.php',
		'DuplicatorPro\Aws\Common\Exception\ServiceResponseException' => __DIR__ . '/Aws/Common/Exception/ServiceResponseException.php',
		'DuplicatorPro\Aws\Common\Exception\TransferException' => __DIR__ . '/Aws/Common/Exception/TransferException.php',
		'DuplicatorPro\Aws\Common\Exception\UnexpectedValueException' => __DIR__ . '/Aws/Common/Exception/UnexpectedValueException.php',
		'DuplicatorPro\Aws\Common\Facade\facade-classes' => __DIR__ . '/Aws/Common/Facade/facade-classes.php',
		'DuplicatorPro\Aws\Common\Facade\Facade' => __DIR__ . '/Aws/Common/Facade/Facade.php',
		'DuplicatorPro\Aws\Common\Facade\FacadeInterface' => __DIR__ . '/Aws/Common/Facade/FacadeInterface.php',
		'DuplicatorPro\Aws\Common\Hash\ChunkHash' => __DIR__ . '/Aws/Common/Hash/ChunkHash.php',
		'DuplicatorPro\Aws\Common\Hash\ChunkHashInterface' => __DIR__ . '/Aws/Common/Hash/ChunkHashInterface.php',
		'DuplicatorPro\Aws\Common\Hash\HashUtils' => __DIR__ . '/Aws/Common/Hash/HashUtils.php',
		'DuplicatorPro\Aws\Common\Hash\TreeHash' => __DIR__ . '/Aws/Common/Hash/TreeHash.php',
		'DuplicatorPro\Aws\Common\HostNameUtils' => __DIR__ . '/Aws/Common/HostNameUtils.php',
		'DuplicatorPro\Aws\Common\InstanceMetadata\InstanceMetadataClient' => __DIR__ . '/Aws/Common/InstanceMetadata/InstanceMetadataClient.php',
		'DuplicatorPro\Aws\Common\InstanceMetadata\Waiter\ServiceAvailable' => __DIR__ . '/Aws/Common/InstanceMetadata/Waiter/ServiceAvailable.php',
		'DuplicatorPro\Aws\Common\Iterator\AwsResourceIterator' => __DIR__ . '/Aws/Common/Iterator/AwsResourceIterator.php',
		'DuplicatorPro\Aws\Common\Iterator\AwsResourceIteratorFactory' => __DIR__ . '/Aws/Common/Iterator/AwsResourceIteratorFactory.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\AbstractTransfer' => __DIR__ . '/Aws/Common/Model/MultipartUpload/AbstractTransfer.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\AbstractTransferState' => __DIR__ . '/Aws/Common/Model/MultipartUpload/AbstractTransferState.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\AbstractUploadBuilder' => __DIR__ . '/Aws/Common/Model/MultipartUpload/AbstractUploadBuilder.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\AbstractUploadId' => __DIR__ . '/Aws/Common/Model/MultipartUpload/AbstractUploadId.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\AbstractUploadPart' => __DIR__ . '/Aws/Common/Model/MultipartUpload/AbstractUploadPart.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\TransferInterface' => __DIR__ . '/Aws/Common/Model/MultipartUpload/TransferInterface.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\TransferStateInterface' => __DIR__ . '/Aws/Common/Model/MultipartUpload/TransferStateInterface.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\UploadIdInterface' => __DIR__ . '/Aws/Common/Model/MultipartUpload/UploadIdInterface.php',
		'DuplicatorPro\Aws\Common\Model\MultipartUpload\UploadPartInterface' => __DIR__ . '/Aws/Common/Model/MultipartUpload/UploadPartInterface.php',
		'DuplicatorPro\Aws\Common\Resources\aws-config' => __DIR__ . '/Aws/Common/Resources/aws-config.php',
		'DuplicatorPro\Aws\Common\Resources\public-endpoints' => __DIR__ . '/Aws/Common/Resources/public-endpoints.php',
		'DuplicatorPro\Aws\Common\Resources\sdk1-config' => __DIR__ . '/Aws/Common/Resources/sdk1-config.php',
		'DuplicatorPro\Aws\Common\RulesEndpointProvider' => __DIR__ . '/Aws/Common/RulesEndpointProvider.php',
		'DuplicatorPro\Aws\Common\Signature\AbstractSignature' => __DIR__ . '/Aws/Common/Signature/AbstractSignature.php',
		'DuplicatorPro\Aws\Common\Signature\EndpointSignatureInterface' => __DIR__ . '/Aws/Common/Signature/EndpointSignatureInterface.php',
		'DuplicatorPro\Aws\Common\Signature\SignatureInterface' => __DIR__ . '/Aws/Common/Signature/SignatureInterface.php',
		'DuplicatorPro\Aws\Common\Signature\SignatureListener' => __DIR__ . '/Aws/Common/Signature/SignatureListener.php',
		'DuplicatorPro\Aws\Common\Signature\SignatureV2' => __DIR__ . '/Aws/Common/Signature/SignatureV2.php',
		'DuplicatorPro\Aws\Common\Signature\SignatureV3Https' => __DIR__ . '/Aws/Common/Signature/SignatureV3Https.php',
		'DuplicatorPro\Aws\Common\Signature\SignatureV4' => __DIR__ . '/Aws/Common/Signature/SignatureV4.php',
		'DuplicatorPro\Aws\Common\Waiter\AbstractResourceWaiter' => __DIR__ . '/Aws/Common/Waiter/AbstractResourceWaiter.php',
		'DuplicatorPro\Aws\Common\Waiter\AbstractWaiter' => __DIR__ . '/Aws/Common/Waiter/AbstractWaiter.php',
		'DuplicatorPro\Aws\Common\Waiter\CallableWaiter' => __DIR__ . '/Aws/Common/Waiter/CallableWaiter.php',
		'DuplicatorPro\Aws\Common\Waiter\CompositeWaiterFactory' => __DIR__ . '/Aws/Common/Waiter/CompositeWaiterFactory.php',
		'DuplicatorPro\Aws\Common\Waiter\ConfigResourceWaiter' => __DIR__ . '/Aws/Common/Waiter/ConfigResourceWaiter.php',
		'DuplicatorPro\Aws\Common\Waiter\ResourceWaiterInterface' => __DIR__ . '/Aws/Common/Waiter/ResourceWaiterInterface.php',
		'DuplicatorPro\Aws\Common\Waiter\WaiterClassFactory' => __DIR__ . '/Aws/Common/Waiter/WaiterClassFactory.php',
		'DuplicatorPro\Aws\Common\Waiter\WaiterConfig' => __DIR__ . '/Aws/Common/Waiter/WaiterConfig.php',
		'DuplicatorPro\Aws\Common\Waiter\WaiterConfigFactory' => __DIR__ . '/Aws/Common/Waiter/WaiterConfigFactory.php',
		'DuplicatorPro\Aws\Common\Waiter\WaiterFactoryInterface' => __DIR__ . '/Aws/Common/Waiter/WaiterFactoryInterface.php',
		'DuplicatorPro\Aws\Common\Waiter\WaiterInterface' => __DIR__ . '/Aws/Common/Waiter/WaiterInterface.php',
		'DuplicatorPro\Aws\S3\AcpListener' => __DIR__ . '/Aws/S3/AcpListener.php',
		'DuplicatorPro\Aws\S3\BucketStyleListener' => __DIR__ . '/Aws/S3/BucketStyleListener.php',
		'DuplicatorPro\Aws\S3\Command\S3Command' => __DIR__ . '/Aws/S3/Command/S3Command.php',
		'DuplicatorPro\Aws\S3\Enum\CannedAcl' => __DIR__ . '/Aws/S3/Enum/CannedAcl.php',
		'DuplicatorPro\Aws\S3\Enum\EncodingType' => __DIR__ . '/Aws/S3/Enum/EncodingType.php',
		'DuplicatorPro\Aws\S3\Enum\Event' => __DIR__ . '/Aws/S3/Enum/Event.php',
		'DuplicatorPro\Aws\S3\Enum\GranteeType' => __DIR__ . '/Aws/S3/Enum/GranteeType.php',
		'DuplicatorPro\Aws\S3\Enum\Group' => __DIR__ . '/Aws/S3/Enum/Group.php',
		'DuplicatorPro\Aws\S3\Enum\MetadataDirective' => __DIR__ . '/Aws/S3/Enum/MetadataDirective.php',
		'DuplicatorPro\Aws\S3\Enum\MFADelete' => __DIR__ . '/Aws/S3/Enum/MFADelete.php',
		'DuplicatorPro\Aws\S3\Enum\Payer' => __DIR__ . '/Aws/S3/Enum/Payer.php',
		'DuplicatorPro\Aws\S3\Enum\Permission' => __DIR__ . '/Aws/S3/Enum/Permission.php',
		'DuplicatorPro\Aws\S3\Enum\Protocol' => __DIR__ . '/Aws/S3/Enum/Protocol.php',
		'DuplicatorPro\Aws\S3\Enum\ServerSideEncryption' => __DIR__ . '/Aws/S3/Enum/ServerSideEncryption.php',
		'DuplicatorPro\Aws\S3\Enum\Status' => __DIR__ . '/Aws/S3/Enum/Status.php',
		'DuplicatorPro\Aws\S3\Enum\Storage' => __DIR__ . '/Aws/S3/Enum/Storage.php',
		'DuplicatorPro\Aws\S3\Enum\StorageClass' => __DIR__ . '/Aws/S3/Enum/StorageClass.php',
		'DuplicatorPro\Aws\S3\Exception\AccessDeniedException' => __DIR__ . '/Aws/S3/Exception/AccessDeniedException.php',
		'DuplicatorPro\Aws\S3\Exception\AccountProblemException' => __DIR__ . '/Aws/S3/Exception/AccountProblemException.php',
		'DuplicatorPro\Aws\S3\Exception\AmbiguousGrantByEmailAddressException' => __DIR__ . '/Aws/S3/Exception/AmbiguousGrantByEmailAddressException.php',
		'DuplicatorPro\Aws\S3\Exception\BadDigestException' => __DIR__ . '/Aws/S3/Exception/BadDigestException.php',
		'DuplicatorPro\Aws\S3\Exception\BucketAlreadyExistsException' => __DIR__ . '/Aws/S3/Exception/BucketAlreadyExistsException.php',
		'DuplicatorPro\Aws\S3\Exception\BucketAlreadyOwnedByYouException' => __DIR__ . '/Aws/S3/Exception/BucketAlreadyOwnedByYouException.php',
		'DuplicatorPro\Aws\S3\Exception\BucketNotEmptyException' => __DIR__ . '/Aws/S3/Exception/BucketNotEmptyException.php',
		'DuplicatorPro\Aws\S3\Exception\CredentialsNotSupportedException' => __DIR__ . '/Aws/S3/Exception/CredentialsNotSupportedException.php',
		'DuplicatorPro\Aws\S3\Exception\CrossLocationLoggingProhibitedException' => __DIR__ . '/Aws/S3/Exception/CrossLocationLoggingProhibitedException.php',
		'DuplicatorPro\Aws\S3\Exception\DeleteMultipleObjectsException' => __DIR__ . '/Aws/S3/Exception/DeleteMultipleObjectsException.php',
		'DuplicatorPro\Aws\S3\Exception\EntityTooLargeException' => __DIR__ . '/Aws/S3/Exception/EntityTooLargeException.php',
		'DuplicatorPro\Aws\S3\Exception\EntityTooSmallException' => __DIR__ . '/Aws/S3/Exception/EntityTooSmallException.php',
		'DuplicatorPro\Aws\S3\Exception\ExpiredTokenException' => __DIR__ . '/Aws/S3/Exception/ExpiredTokenException.php',
		'DuplicatorPro\Aws\S3\Exception\IllegalVersioningConfigurationException' => __DIR__ . '/Aws/S3/Exception/IllegalVersioningConfigurationException.php',
		'DuplicatorPro\Aws\S3\Exception\IncompleteBodyException' => __DIR__ . '/Aws/S3/Exception/IncompleteBodyException.php',
		'DuplicatorPro\Aws\S3\Exception\IncorrectNumberOfFilesInPostRequestException' => __DIR__ . '/Aws/S3/Exception/IncorrectNumberOfFilesInPostRequestException.php',
		'DuplicatorPro\Aws\S3\Exception\InlineDataTooLargeException' => __DIR__ . '/Aws/S3/Exception/InlineDataTooLargeException.php',
		'DuplicatorPro\Aws\S3\Exception\InternalErrorException' => __DIR__ . '/Aws/S3/Exception/InternalErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidAccessKeyIdException' => __DIR__ . '/Aws/S3/Exception/InvalidAccessKeyIdException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidAddressingHeaderException' => __DIR__ . '/Aws/S3/Exception/InvalidAddressingHeaderException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidArgumentException' => __DIR__ . '/Aws/S3/Exception/InvalidArgumentException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidBucketNameException' => __DIR__ . '/Aws/S3/Exception/InvalidBucketNameException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidBucketStateException' => __DIR__ . '/Aws/S3/Exception/InvalidBucketStateException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidDigestException' => __DIR__ . '/Aws/S3/Exception/InvalidDigestException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidLocationConstraintException' => __DIR__ . '/Aws/S3/Exception/InvalidLocationConstraintException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidPartException' => __DIR__ . '/Aws/S3/Exception/InvalidPartException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidPartOrderException' => __DIR__ . '/Aws/S3/Exception/InvalidPartOrderException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidPayerException' => __DIR__ . '/Aws/S3/Exception/InvalidPayerException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidPolicyDocumentException' => __DIR__ . '/Aws/S3/Exception/InvalidPolicyDocumentException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidRangeException' => __DIR__ . '/Aws/S3/Exception/InvalidRangeException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidRequestException' => __DIR__ . '/Aws/S3/Exception/InvalidRequestException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidSecurityException' => __DIR__ . '/Aws/S3/Exception/InvalidSecurityException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidSOAPRequestException' => __DIR__ . '/Aws/S3/Exception/InvalidSOAPRequestException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidStorageClassException' => __DIR__ . '/Aws/S3/Exception/InvalidStorageClassException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidTagErrorException' => __DIR__ . '/Aws/S3/Exception/InvalidTagErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidTargetBucketForLoggingException' => __DIR__ . '/Aws/S3/Exception/InvalidTargetBucketForLoggingException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidTokenException' => __DIR__ . '/Aws/S3/Exception/InvalidTokenException.php',
		'DuplicatorPro\Aws\S3\Exception\InvalidURIException' => __DIR__ . '/Aws/S3/Exception/InvalidURIException.php',
		'DuplicatorPro\Aws\S3\Exception\KeyTooLongException' => __DIR__ . '/Aws/S3/Exception/KeyTooLongException.php',
		'DuplicatorPro\Aws\S3\Exception\MalformedACLErrorException' => __DIR__ . '/Aws/S3/Exception/MalformedACLErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\MalformedPOSTRequestException' => __DIR__ . '/Aws/S3/Exception/MalformedPOSTRequestException.php',
		'DuplicatorPro\Aws\S3\Exception\MalformedXMLException' => __DIR__ . '/Aws/S3/Exception/MalformedXMLException.php',
		'DuplicatorPro\Aws\S3\Exception\MaxMessageLengthExceededException' => __DIR__ . '/Aws/S3/Exception/MaxMessageLengthExceededException.php',
		'DuplicatorPro\Aws\S3\Exception\MaxPostPreDataLengthExceededErrorException' => __DIR__ . '/Aws/S3/Exception/MaxPostPreDataLengthExceededErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\MetadataTooLargeException' => __DIR__ . '/Aws/S3/Exception/MetadataTooLargeException.php',
		'DuplicatorPro\Aws\S3\Exception\MethodNotAllowedException' => __DIR__ . '/Aws/S3/Exception/MethodNotAllowedException.php',
		'DuplicatorPro\Aws\S3\Exception\MissingAttachmentException' => __DIR__ . '/Aws/S3/Exception/MissingAttachmentException.php',
		'DuplicatorPro\Aws\S3\Exception\MissingContentLengthException' => __DIR__ . '/Aws/S3/Exception/MissingContentLengthException.php',
		'DuplicatorPro\Aws\S3\Exception\MissingRequestBodyErrorException' => __DIR__ . '/Aws/S3/Exception/MissingRequestBodyErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\MissingSecurityElementException' => __DIR__ . '/Aws/S3/Exception/MissingSecurityElementException.php',
		'DuplicatorPro\Aws\S3\Exception\MissingSecurityHeaderException' => __DIR__ . '/Aws/S3/Exception/MissingSecurityHeaderException.php',
		'DuplicatorPro\Aws\S3\Exception\NoLoggingStatusForKeyException' => __DIR__ . '/Aws/S3/Exception/NoLoggingStatusForKeyException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchBucketException' => __DIR__ . '/Aws/S3/Exception/NoSuchBucketException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchBucketPolicyException' => __DIR__ . '/Aws/S3/Exception/NoSuchBucketPolicyException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchCORSConfigurationException' => __DIR__ . '/Aws/S3/Exception/NoSuchCORSConfigurationException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchKeyException' => __DIR__ . '/Aws/S3/Exception/NoSuchKeyException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchLifecycleConfigurationException' => __DIR__ . '/Aws/S3/Exception/NoSuchLifecycleConfigurationException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchTagSetException' => __DIR__ . '/Aws/S3/Exception/NoSuchTagSetException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchUploadException' => __DIR__ . '/Aws/S3/Exception/NoSuchUploadException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchVersionException' => __DIR__ . '/Aws/S3/Exception/NoSuchVersionException.php',
		'DuplicatorPro\Aws\S3\Exception\NoSuchWebsiteConfigurationException' => __DIR__ . '/Aws/S3/Exception/NoSuchWebsiteConfigurationException.php',
		'DuplicatorPro\Aws\S3\Exception\NotImplementedException' => __DIR__ . '/Aws/S3/Exception/NotImplementedException.php',
		'DuplicatorPro\Aws\S3\Exception\NotSignedUpException' => __DIR__ . '/Aws/S3/Exception/NotSignedUpException.php',
		'DuplicatorPro\Aws\S3\Exception\NotSuchBucketPolicyException' => __DIR__ . '/Aws/S3/Exception/NotSuchBucketPolicyException.php',
		'DuplicatorPro\Aws\S3\Exception\ObjectAlreadyInActiveTierErrorException' => __DIR__ . '/Aws/S3/Exception/ObjectAlreadyInActiveTierErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\ObjectNotInActiveTierErrorException' => __DIR__ . '/Aws/S3/Exception/ObjectNotInActiveTierErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\OperationAbortedException' => __DIR__ . '/Aws/S3/Exception/OperationAbortedException.php',
		'DuplicatorPro\Aws\S3\Exception\Parser\S3ExceptionParser' => __DIR__ . '/Aws/S3/Exception/Parser/S3ExceptionParser.php',
		'DuplicatorPro\Aws\S3\Exception\PermanentRedirectException' => __DIR__ . '/Aws/S3/Exception/PermanentRedirectException.php',
		'DuplicatorPro\Aws\S3\Exception\PreconditionFailedException' => __DIR__ . '/Aws/S3/Exception/PreconditionFailedException.php',
		'DuplicatorPro\Aws\S3\Exception\RedirectException' => __DIR__ . '/Aws/S3/Exception/RedirectException.php',
		'DuplicatorPro\Aws\S3\Exception\RequestIsNotMultiPartContentException' => __DIR__ . '/Aws/S3/Exception/RequestIsNotMultiPartContentException.php',
		'DuplicatorPro\Aws\S3\Exception\RequestTimeoutException' => __DIR__ . '/Aws/S3/Exception/RequestTimeoutException.php',
		'DuplicatorPro\Aws\S3\Exception\RequestTimeTooSkewedException' => __DIR__ . '/Aws/S3/Exception/RequestTimeTooSkewedException.php',
		'DuplicatorPro\Aws\S3\Exception\RequestTorrentOfBucketErrorException' => __DIR__ . '/Aws/S3/Exception/RequestTorrentOfBucketErrorException.php',
		'DuplicatorPro\Aws\S3\Exception\S3Exception' => __DIR__ . '/Aws/S3/Exception/S3Exception.php',
		'DuplicatorPro\Aws\S3\Exception\ServiceUnavailableException' => __DIR__ . '/Aws/S3/Exception/ServiceUnavailableException.php',
		'DuplicatorPro\Aws\S3\Exception\SignatureDoesNotMatchException' => __DIR__ . '/Aws/S3/Exception/SignatureDoesNotMatchException.php',
		'DuplicatorPro\Aws\S3\Exception\SlowDownException' => __DIR__ . '/Aws/S3/Exception/SlowDownException.php',
		'DuplicatorPro\Aws\S3\Exception\TemporaryRedirectException' => __DIR__ . '/Aws/S3/Exception/TemporaryRedirectException.php',
		'DuplicatorPro\Aws\S3\Exception\TokenRefreshRequiredException' => __DIR__ . '/Aws/S3/Exception/TokenRefreshRequiredException.php',
		'DuplicatorPro\Aws\S3\Exception\TooManyBucketsException' => __DIR__ . '/Aws/S3/Exception/TooManyBucketsException.php',
		'DuplicatorPro\Aws\S3\Exception\UnexpectedContentException' => __DIR__ . '/Aws/S3/Exception/UnexpectedContentException.php',
		'DuplicatorPro\Aws\S3\Exception\UnresolvableGrantByEmailAddressException' => __DIR__ . '/Aws/S3/Exception/UnresolvableGrantByEmailAddressException.php',
		'DuplicatorPro\Aws\S3\Exception\UserKeyMustBeSpecifiedException' => __DIR__ . '/Aws/S3/Exception/UserKeyMustBeSpecifiedException.php',
		'DuplicatorPro\Aws\S3\IncompleteMultipartUploadChecker' => __DIR__ . '/Aws/S3/IncompleteMultipartUploadChecker.php',
		'DuplicatorPro\Aws\S3\Iterator\ListBucketsIterator' => __DIR__ . '/Aws/S3/Iterator/ListBucketsIterator.php',
		'DuplicatorPro\Aws\S3\Iterator\ListMultipartUploadsIterator' => __DIR__ . '/Aws/S3/Iterator/ListMultipartUploadsIterator.php',
		'DuplicatorPro\Aws\S3\Iterator\ListObjectsIterator' => __DIR__ . '/Aws/S3/Iterator/ListObjectsIterator.php',
		'DuplicatorPro\Aws\S3\Iterator\ListObjectVersionsIterator' => __DIR__ . '/Aws/S3/Iterator/ListObjectVersionsIterator.php',
		'DuplicatorPro\Aws\S3\Iterator\OpendirIterator' => __DIR__ . '/Aws/S3/Iterator/OpendirIterator.php',
		'DuplicatorPro\Aws\S3\Model\Acp' => __DIR__ . '/Aws/S3/Model/Acp.php',
		'DuplicatorPro\Aws\S3\Model\AcpBuilder' => __DIR__ . '/Aws/S3/Model/AcpBuilder.php',
		'DuplicatorPro\Aws\S3\Model\ClearBucket' => __DIR__ . '/Aws/S3/Model/ClearBucket.php',
		'DuplicatorPro\Aws\S3\Model\DeleteObjectsBatch' => __DIR__ . '/Aws/S3/Model/DeleteObjectsBatch.php',
		'DuplicatorPro\Aws\S3\Model\DeleteObjectsTransfer' => __DIR__ . '/Aws/S3/Model/DeleteObjectsTransfer.php',
		'DuplicatorPro\Aws\S3\Model\Grant' => __DIR__ . '/Aws/S3/Model/Grant.php',
		'DuplicatorPro\Aws\S3\Model\Grantee' => __DIR__ . '/Aws/S3/Model/Grantee.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\AbstractTransfer' => __DIR__ . '/Aws/S3/Model/MultipartUpload/AbstractTransfer.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\ParallelTransfer' => __DIR__ . '/Aws/S3/Model/MultipartUpload/ParallelTransfer.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\SerialTransfer' => __DIR__ . '/Aws/S3/Model/MultipartUpload/SerialTransfer.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\TransferState' => __DIR__ . '/Aws/S3/Model/MultipartUpload/TransferState.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\UploadBuilder' => __DIR__ . '/Aws/S3/Model/MultipartUpload/UploadBuilder.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\UploadId' => __DIR__ . '/Aws/S3/Model/MultipartUpload/UploadId.php',
		'DuplicatorPro\Aws\S3\Model\MultipartUpload\UploadPart' => __DIR__ . '/Aws/S3/Model/MultipartUpload/UploadPart.php',
		'DuplicatorPro\Aws\S3\Model\PostObject' => __DIR__ . '/Aws/S3/Model/PostObject.php',
		'DuplicatorPro\Aws\S3\Resources\s3-2006-03-01' => __DIR__ . '/Aws/S3/Resources/s3-2006-03-01.php',
		'DuplicatorPro\Aws\S3\ResumableDownload' => __DIR__ . '/Aws/S3/ResumableDownload.php',
		'DuplicatorPro\Aws\S3\S3Client' => __DIR__ . '/Aws/S3/S3Client.php',
		'DuplicatorPro\Aws\S3\S3Md5Listener' => __DIR__ . '/Aws/S3/S3Md5Listener.php',
		'DuplicatorPro\Aws\S3\S3Signature' => __DIR__ . '/Aws/S3/S3Signature.php',
		'DuplicatorPro\Aws\S3\S3SignatureInterface' => __DIR__ . '/Aws/S3/S3SignatureInterface.php',
		'DuplicatorPro\Aws\S3\S3SignatureV4' => __DIR__ . '/Aws/S3/S3SignatureV4.php',
		'DuplicatorPro\Aws\S3\SocketTimeoutChecker' => __DIR__ . '/Aws/S3/SocketTimeoutChecker.php',
		'DuplicatorPro\Aws\S3\SseCpkListener' => __DIR__ . '/Aws/S3/SseCpkListener.php',
		'DuplicatorPro\Aws\S3\StreamWrapper' => __DIR__ . '/Aws/S3/StreamWrapper.php',
		'DuplicatorPro\Aws\S3\Sync\AbstractSync' => __DIR__ . '/Aws/S3/Sync/AbstractSync.php',
		'DuplicatorPro\Aws\S3\Sync\AbstractSyncBuilder' => __DIR__ . '/Aws/S3/Sync/AbstractSyncBuilder.php',
		'DuplicatorPro\Aws\S3\Sync\ChangedFilesIterator' => __DIR__ . '/Aws/S3/Sync/ChangedFilesIterator.php',
		'DuplicatorPro\Aws\S3\Sync\DownloadSync' => __DIR__ . '/Aws/S3/Sync/DownloadSync.php',
		'DuplicatorPro\Aws\S3\Sync\DownloadSyncBuilder' => __DIR__ . '/Aws/S3/Sync/DownloadSyncBuilder.php',
		'DuplicatorPro\Aws\S3\Sync\FilenameConverterInterface' => __DIR__ . '/Aws/S3/Sync/FilenameConverterInterface.php',
		'DuplicatorPro\Aws\S3\Sync\KeyConverter' => __DIR__ . '/Aws/S3/Sync/KeyConverter.php',
		'DuplicatorPro\Aws\S3\Sync\UploadSync' => __DIR__ . '/Aws/S3/Sync/UploadSync.php',
		'DuplicatorPro\Aws\S3\Sync\UploadSyncBuilder' => __DIR__ . '/Aws/S3/Sync/UploadSyncBuilder.php',		
		'DuplicatorPro\Guzzle\Common\AbstractHasDispatcher' => __DIR__ . '/Guzzle/Common/AbstractHasDispatcher.php',
		'DuplicatorPro\Guzzle\Common\Collection' => __DIR__ . '/Guzzle/Common/Collection.php',
		'DuplicatorPro\Guzzle\Common\Event' => __DIR__ . '/Guzzle/Common/Event.php',
		'DuplicatorPro\Guzzle\Common\Exception\BadMethodCallException' => __DIR__ . '/Guzzle/Common/Exception/BadMethodCallException.php',
		'DuplicatorPro\Guzzle\Common\Exception\ExceptionCollection' => __DIR__ . '/Guzzle/Common/Exception/ExceptionCollection.php',
		'DuplicatorPro\Guzzle\Common\Exception\GuzzleException' => __DIR__ . '/Guzzle/Common/Exception/GuzzleException.php',
		'DuplicatorPro\Guzzle\Common\Exception\InvalidArgumentException' => __DIR__ . '/Guzzle/Common/Exception/InvalidArgumentException.php',
		'DuplicatorPro\Guzzle\Common\Exception\RuntimeException' => __DIR__ . '/Guzzle/Common/Exception/RuntimeException.php',
		'DuplicatorPro\Guzzle\Common\Exception\UnexpectedValueException' => __DIR__ . '/Guzzle/Common/Exception/UnexpectedValueException.php',
		'DuplicatorPro\Guzzle\Common\FromConfigInterface' => __DIR__ . '/Guzzle/Common/FromConfigInterface.php',
		'DuplicatorPro\Guzzle\Common\HasDispatcherInterface' => __DIR__ . '/Guzzle/Common/HasDispatcherInterface.php',
		'DuplicatorPro\Guzzle\Common\ToArrayInterface' => __DIR__ . '/Guzzle/Common/ToArrayInterface.php',
		'DuplicatorPro\Guzzle\Common\Version' => __DIR__ . '/Guzzle/Common/Version.php',
		'DuplicatorPro\Guzzle\Http\AbstractEntityBodyDecorator' => __DIR__ . '/Guzzle/Http/AbstractEntityBodyDecorator.php',
		'DuplicatorPro\Guzzle\Http\CachingEntityBody' => __DIR__ . '/Guzzle/Http/CachingEntityBody.php',
		'DuplicatorPro\Guzzle\Http\Client' => __DIR__ . '/Guzzle/Http/Client.php',
		'DuplicatorPro\Guzzle\Http\ClientInterface' => __DIR__ . '/Guzzle/Http/ClientInterface.php',
		'DuplicatorPro\Guzzle\Http\Curl\CurlHandle' => __DIR__ . '/Guzzle/Http/Curl/CurlHandle.php',
		'DuplicatorPro\Guzzle\Http\Curl\CurlMulti' => __DIR__ . '/Guzzle/Http/Curl/CurlMulti.php',
		'DuplicatorPro\Guzzle\Http\Curl\CurlMultiInterface' => __DIR__ . '/Guzzle/Http/Curl/CurlMultiInterface.php',
		'DuplicatorPro\Guzzle\Http\Curl\CurlMultiProxy' => __DIR__ . '/Guzzle/Http/Curl/CurlMultiProxy.php',
		'DuplicatorPro\Guzzle\Http\Curl\CurlVersion' => __DIR__ . '/Guzzle/Http/Curl/CurlVersion.php',
		'DuplicatorPro\Guzzle\Http\Curl\RequestMediator' => __DIR__ . '/Guzzle/Http/Curl/RequestMediator.php',
		'DuplicatorPro\Guzzle\Http\EntityBody' => __DIR__ . '/Guzzle/Http/EntityBody.php',
		'DuplicatorPro\Guzzle\Http\EntityBodyInterface' => __DIR__ . '/Guzzle/Http/EntityBodyInterface.php',
		'DuplicatorPro\Guzzle\Http\Exception\BadResponseException' => __DIR__ . '/Guzzle/Http/Exception/BadResponseException.php',
		'DuplicatorPro\Guzzle\Http\Exception\ClientErrorResponseException' => __DIR__ . '/Guzzle/Http/Exception/ClientErrorResponseException.php',
		'DuplicatorPro\Guzzle\Http\Exception\CouldNotRewindStreamException' => __DIR__ . '/Guzzle/Http/Exception/CouldNotRewindStreamException.php',
		'DuplicatorPro\Guzzle\Http\Exception\CurlException' => __DIR__ . '/Guzzle/Http/Exception/CurlException.php',
		'DuplicatorPro\Guzzle\Http\Exception\HttpException' => __DIR__ . '/Guzzle/Http/Exception/HttpException.php',
		'DuplicatorPro\Guzzle\Http\Exception\MultiTransferException' => __DIR__ . '/Guzzle/Http/Exception/MultiTransferException.php',
		'DuplicatorPro\Guzzle\Http\Exception\RequestException' => __DIR__ . '/Guzzle/Http/Exception/RequestException.php',
		'DuplicatorPro\Guzzle\Http\Exception\ServerErrorResponseException' => __DIR__ . '/Guzzle/Http/Exception/ServerErrorResponseException.php',
		'DuplicatorPro\Guzzle\Http\Exception\TooManyRedirectsException' => __DIR__ . '/Guzzle/Http/Exception/TooManyRedirectsException.php',
		'DuplicatorPro\Guzzle\Http\IoEmittingEntityBody' => __DIR__ . '/Guzzle/Http/IoEmittingEntityBody.php',
		'DuplicatorPro\Guzzle\Http\Message\AbstractMessage' => __DIR__ . '/Guzzle/Http/Message/AbstractMessage.php',
		'DuplicatorPro\Guzzle\Http\Message\EntityEnclosingRequest' => __DIR__ . '/Guzzle/Http/Message/EntityEnclosingRequest.php',
		'DuplicatorPro\Guzzle\Http\Message\EntityEnclosingRequestInterface' => __DIR__ . '/Guzzle/Http/Message/EntityEnclosingRequestInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\Header\CacheControl' => __DIR__ . '/Guzzle/Http/Message/Header/CacheControl.php',
		'DuplicatorPro\Guzzle\Http\Message\Header\HeaderCollection' => __DIR__ . '/Guzzle/Http/Message/Header/HeaderCollection.php',
		'DuplicatorPro\Guzzle\Http\Message\Header\HeaderFactory' => __DIR__ . '/Guzzle/Http/Message/Header/HeaderFactory.php',
		'DuplicatorPro\Guzzle\Http\Message\Header\HeaderFactoryInterface' => __DIR__ . '/Guzzle/Http/Message/Header/HeaderFactoryInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\Header\HeaderInterface' => __DIR__ . '/Guzzle/Http/Message/Header/HeaderInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\Header\Link' => __DIR__ . '/Guzzle/Http/Message/Header/Link.php',
		'DuplicatorPro\Guzzle\Http\Message\Header' => __DIR__ . '/Guzzle/Http/Message/Header.php',
		'DuplicatorPro\Guzzle\Http\Message\MessageInterface' => __DIR__ . '/Guzzle/Http/Message/MessageInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\PostFile' => __DIR__ . '/Guzzle/Http/Message/PostFile.php',
		'DuplicatorPro\Guzzle\Http\Message\PostFileInterface' => __DIR__ . '/Guzzle/Http/Message/PostFileInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\Request' => __DIR__ . '/Guzzle/Http/Message/Request.php',
		'DuplicatorPro\Guzzle\Http\Message\RequestFactory' => __DIR__ . '/Guzzle/Http/Message/RequestFactory.php',
		'DuplicatorPro\Guzzle\Http\Message\RequestFactoryInterface' => __DIR__ . '/Guzzle/Http/Message/RequestFactoryInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\RequestInterface' => __DIR__ . '/Guzzle/Http/Message/RequestInterface.php',
		'DuplicatorPro\Guzzle\Http\Message\Response' => __DIR__ . '/Guzzle/Http/Message/Response.php',
		'DuplicatorPro\Guzzle\Http\Mimetypes' => __DIR__ . '/Guzzle/Http/Mimetypes.php',
		'DuplicatorPro\Guzzle\Http\QueryAggregator\CommaAggregator' => __DIR__ . '/Guzzle/Http/QueryAggregator/CommaAggregator.php',
		'DuplicatorPro\Guzzle\Http\QueryAggregator\DuplicateAggregator' => __DIR__ . '/Guzzle/Http/QueryAggregator/DuplicateAggregator.php',
		'DuplicatorPro\Guzzle\Http\QueryAggregator\PhpAggregator' => __DIR__ . '/Guzzle/Http/QueryAggregator/PhpAggregator.php',
		'DuplicatorPro\Guzzle\Http\QueryAggregator\QueryAggregatorInterface' => __DIR__ . '/Guzzle/Http/QueryAggregator/QueryAggregatorInterface.php',
		'DuplicatorPro\Guzzle\Http\QueryString' => __DIR__ . '/Guzzle/Http/QueryString.php',
		'DuplicatorPro\Guzzle\Http\ReadLimitEntityBody' => __DIR__ . '/Guzzle/Http/ReadLimitEntityBody.php',
		'DuplicatorPro\Guzzle\Http\RedirectPlugin' => __DIR__ . '/Guzzle/Http/RedirectPlugin.php',
		'DuplicatorPro\Guzzle\Http\StaticClient' => __DIR__ . '/Guzzle/Http/StaticClient.php',
		'DuplicatorPro\Guzzle\Http\Url' => __DIR__ . '/Guzzle/Http/Url.php',
		'DuplicatorPro\Guzzle\Inflection\Inflector' => __DIR__ . '/Guzzle/Inflection/Inflector.php',
		'DuplicatorPro\Guzzle\Inflection\InflectorInterface' => __DIR__ . '/Guzzle/Inflection/InflectorInterface.php',
		'DuplicatorPro\Guzzle\Inflection\MemoizingInflector' => __DIR__ . '/Guzzle/Inflection/MemoizingInflector.php',
		'DuplicatorPro\Guzzle\Inflection\PreComputedInflector' => __DIR__ . '/Guzzle/Inflection/PreComputedInflector.php',
		'DuplicatorPro\Guzzle\Parser\Cookie\CookieParser' => __DIR__ . '/Guzzle/Parser/Cookie/CookieParser.php',
		'DuplicatorPro\Guzzle\Parser\Cookie\CookieParserInterface' => __DIR__ . '/Guzzle/Parser/Cookie/CookieParserInterface.php',
		'DuplicatorPro\Guzzle\Parser\Message\AbstractMessageParser' => __DIR__ . '/Guzzle/Parser/Message/AbstractMessageParser.php',
		'DuplicatorPro\Guzzle\Parser\Message\MessageParser' => __DIR__ . '/Guzzle/Parser/Message/MessageParser.php',
		'DuplicatorPro\Guzzle\Parser\Message\MessageParserInterface' => __DIR__ . '/Guzzle/Parser/Message/MessageParserInterface.php',
		'DuplicatorPro\Guzzle\Parser\Message\PeclHttpMessageParser' => __DIR__ . '/Guzzle/Parser/Message/PeclHttpMessageParser.php',
		'DuplicatorPro\Guzzle\Parser\ParserRegistry' => __DIR__ . '/Guzzle/Parser/ParserRegistry.php',
		'DuplicatorPro\Guzzle\Parser\UriTemplate\PeclUriTemplate' => __DIR__ . '/Guzzle/Parser/UriTemplate/PeclUriTemplate.php',
		'DuplicatorPro\Guzzle\Parser\UriTemplate\UriTemplate' => __DIR__ . '/Guzzle/Parser/UriTemplate/UriTemplate.php',
		'DuplicatorPro\Guzzle\Parser\UriTemplate\UriTemplateInterface' => __DIR__ . '/Guzzle/Parser/UriTemplate/UriTemplateInterface.php',
		'DuplicatorPro\Guzzle\Parser\Url\UrlParser' => __DIR__ . '/Guzzle/Parser/Url/UrlParser.php',
		'DuplicatorPro\Guzzle\Parser\Url\UrlParserInterface' => __DIR__ . '/Guzzle/Parser/Url/UrlParserInterface.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\AbstractBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/AbstractBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\AbstractErrorCodeBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/AbstractErrorCodeBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\BackoffLogger' => __DIR__ . '/Guzzle/Plugin/Backoff/BackoffLogger.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\BackoffPlugin' => __DIR__ . '/Guzzle/Plugin/Backoff/BackoffPlugin.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\BackoffStrategyInterface' => __DIR__ . '/Guzzle/Plugin/Backoff/BackoffStrategyInterface.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\CallbackBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/CallbackBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\ConstantBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/ConstantBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\CurlBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/CurlBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\ExponentialBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/ExponentialBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\HttpBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/HttpBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\LinearBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/LinearBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\ReasonPhraseBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/ReasonPhraseBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Plugin\Backoff\TruncatedBackoffStrategy' => __DIR__ . '/Guzzle/Plugin/Backoff/TruncatedBackoffStrategy.php',
		'DuplicatorPro\Guzzle\Service\AbstractConfigLoader' => __DIR__ . '/Guzzle/Service/AbstractConfigLoader.php',
		'DuplicatorPro\Guzzle\Service\Builder\ServiceBuilder' => __DIR__ . '/Guzzle/Service/Builder/ServiceBuilder.php',
		'DuplicatorPro\Guzzle\Service\Builder\ServiceBuilderInterface' => __DIR__ . '/Guzzle/Service/Builder/ServiceBuilderInterface.php',
		'DuplicatorPro\Guzzle\Service\Builder\ServiceBuilderLoader' => __DIR__ . '/Guzzle/Service/Builder/ServiceBuilderLoader.php',
		'DuplicatorPro\Guzzle\Service\CachingConfigLoader' => __DIR__ . '/Guzzle/Service/CachingConfigLoader.php',
		'DuplicatorPro\Guzzle\Service\Client' => __DIR__ . '/Guzzle/Service/Client.php',
		'DuplicatorPro\Guzzle\Service\ClientInterface' => __DIR__ . '/Guzzle/Service/ClientInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\AbstractCommand' => __DIR__ . '/Guzzle/Service/Command/AbstractCommand.php',
		'DuplicatorPro\Guzzle\Service\Command\ClosureCommand' => __DIR__ . '/Guzzle/Service/Command/ClosureCommand.php',
		'DuplicatorPro\Guzzle\Service\Command\CommandInterface' => __DIR__ . '/Guzzle/Service/Command/CommandInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\CreateResponseClassEvent' => __DIR__ . '/Guzzle/Service/Command/CreateResponseClassEvent.php',
		'DuplicatorPro\Guzzle\Service\Command\DefaultRequestSerializer' => __DIR__ . '/Guzzle/Service/Command/DefaultRequestSerializer.php',
		'DuplicatorPro\Guzzle\Service\Command\DefaultResponseParser' => __DIR__ . '/Guzzle/Service/Command/DefaultResponseParser.php',
		'DuplicatorPro\Guzzle\Service\Command\Factory\AliasFactory' => __DIR__ . '/Guzzle/Service/Command/Factory/AliasFactory.php',
		'DuplicatorPro\Guzzle\Service\Command\Factory\CompositeFactory' => __DIR__ . '/Guzzle/Service/Command/Factory/CompositeFactory.php',
		'DuplicatorPro\Guzzle\Service\Command\Factory\ConcreteClassFactory' => __DIR__ . '/Guzzle/Service/Command/Factory/ConcreteClassFactory.php',
		'DuplicatorPro\Guzzle\Service\Command\Factory\FactoryInterface' => __DIR__ . '/Guzzle/Service/Command/Factory/FactoryInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\Factory\MapFactory' => __DIR__ . '/Guzzle/Service/Command/Factory/MapFactory.php',
		'DuplicatorPro\Guzzle\Service\Command\Factory\ServiceDescriptionFactory' => __DIR__ . '/Guzzle/Service/Command/Factory/ServiceDescriptionFactory.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\AbstractRequestVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/AbstractRequestVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\BodyVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/BodyVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\HeaderVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/HeaderVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\JsonVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/JsonVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\PostFieldVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/PostFieldVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\PostFileVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/PostFileVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\QueryVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/QueryVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\RequestVisitorInterface' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/RequestVisitorInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\ResponseBodyVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/ResponseBodyVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Request\XmlVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Request/XmlVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\AbstractResponseVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/AbstractResponseVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\BodyVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/BodyVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\HeaderVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/HeaderVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\JsonVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\ReasonPhraseVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/ReasonPhraseVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\ResponseVisitorInterface' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/ResponseVisitorInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\StatusCodeVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/StatusCodeVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\Response\XmlVisitor' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/Response/XmlVisitor.php',
		'DuplicatorPro\Guzzle\Service\Command\LocationVisitor\VisitorFlyweight' => __DIR__ . '/Guzzle/Service/Command/LocationVisitor/VisitorFlyweight.php',
		'DuplicatorPro\Guzzle\Service\Command\OperationCommand' => __DIR__ . '/Guzzle/Service/Command/OperationCommand.php',
		'DuplicatorPro\Guzzle\Service\Command\OperationResponseParser' => __DIR__ . '/Guzzle/Service/Command/OperationResponseParser.php',
		'DuplicatorPro\Guzzle\Service\Command\RequestSerializerInterface' => __DIR__ . '/Guzzle/Service/Command/RequestSerializerInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\ResponseClassInterface' => __DIR__ . '/Guzzle/Service/Command/ResponseClassInterface.php',
		'DuplicatorPro\Guzzle\Service\Command\ResponseParserInterface' => __DIR__ . '/Guzzle/Service/Command/ResponseParserInterface.php',
		'DuplicatorPro\Guzzle\Service\ConfigLoaderInterface' => __DIR__ . '/Guzzle/Service/ConfigLoaderInterface.php',
		'DuplicatorPro\Guzzle\Service\Description\Operation' => __DIR__ . '/Guzzle/Service/Description/Operation.php',
		'DuplicatorPro\Guzzle\Service\Description\OperationInterface' => __DIR__ . '/Guzzle/Service/Description/OperationInterface.php',
		'DuplicatorPro\Guzzle\Service\Description\Parameter' => __DIR__ . '/Guzzle/Service/Description/Parameter.php',
		'DuplicatorPro\Guzzle\Service\Description\SchemaFormatter' => __DIR__ . '/Guzzle/Service/Description/SchemaFormatter.php',
		'DuplicatorPro\Guzzle\Service\Description\SchemaValidator' => __DIR__ . '/Guzzle/Service/Description/SchemaValidator.php',
		'DuplicatorPro\Guzzle\Service\Description\ServiceDescription' => __DIR__ . '/Guzzle/Service/Description/ServiceDescription.php',
		'DuplicatorPro\Guzzle\Service\Description\ServiceDescriptionInterface' => __DIR__ . '/Guzzle/Service/Description/ServiceDescriptionInterface.php',
		'DuplicatorPro\Guzzle\Service\Description\ServiceDescriptionLoader' => __DIR__ . '/Guzzle/Service/Description/ServiceDescriptionLoader.php',
		'DuplicatorPro\Guzzle\Service\Description\ValidatorInterface' => __DIR__ . '/Guzzle/Service/Description/ValidatorInterface.php',
		'DuplicatorPro\Guzzle\Service\Exception\CommandException' => __DIR__ . '/Guzzle/Service/Exception/CommandException.php',
		'DuplicatorPro\Guzzle\Service\Exception\CommandTransferException' => __DIR__ . '/Guzzle/Service/Exception/CommandTransferException.php',
		'DuplicatorPro\Guzzle\Service\Exception\DescriptionBuilderException' => __DIR__ . '/Guzzle/Service/Exception/DescriptionBuilderException.php',
		'DuplicatorPro\Guzzle\Service\Exception\InconsistentClientTransferException' => __DIR__ . '/Guzzle/Service/Exception/InconsistentClientTransferException.php',
		'DuplicatorPro\Guzzle\Service\Exception\ResponseClassException' => __DIR__ . '/Guzzle/Service/Exception/ResponseClassException.php',
		'DuplicatorPro\Guzzle\Service\Exception\ServiceBuilderException' => __DIR__ . '/Guzzle/Service/Exception/ServiceBuilderException.php',
		'DuplicatorPro\Guzzle\Service\Exception\ServiceNotFoundException' => __DIR__ . '/Guzzle/Service/Exception/ServiceNotFoundException.php',
		'DuplicatorPro\Guzzle\Service\Exception\ValidationException' => __DIR__ . '/Guzzle/Service/Exception/ValidationException.php',
		'DuplicatorPro\Guzzle\Service\Resource\AbstractResourceIteratorFactory' => __DIR__ . '/Guzzle/Service/Resource/AbstractResourceIteratorFactory.php',
		'DuplicatorPro\Guzzle\Service\Resource\CompositeResourceIteratorFactory' => __DIR__ . '/Guzzle/Service/Resource/CompositeResourceIteratorFactory.php',
		'DuplicatorPro\Guzzle\Service\Resource\MapResourceIteratorFactory' => __DIR__ . '/Guzzle/Service/Resource/MapResourceIteratorFactory.php',
		'DuplicatorPro\Guzzle\Service\Resource\Model' => __DIR__ . '/Guzzle/Service/Resource/Model.php',
		'DuplicatorPro\Guzzle\Service\Resource\ResourceIterator' => __DIR__ . '/Guzzle/Service/Resource/ResourceIterator.php',
		'DuplicatorPro\Guzzle\Service\Resource\ResourceIteratorApplyBatched' => __DIR__ . '/Guzzle/Service/Resource/ResourceIteratorApplyBatched.php',
		'DuplicatorPro\Guzzle\Service\Resource\ResourceIteratorClassFactory' => __DIR__ . '/Guzzle/Service/Resource/ResourceIteratorClassFactory.php',
		'DuplicatorPro\Guzzle\Service\Resource\ResourceIteratorFactoryInterface' => __DIR__ . '/Guzzle/Service/Resource/ResourceIteratorFactoryInterface.php',
		'DuplicatorPro\Guzzle\Service\Resource\ResourceIteratorInterface' => __DIR__ . '/Guzzle/Service/Resource/ResourceIteratorInterface.php',
		'DuplicatorPro\Guzzle\Stream\PhpStreamRequestFactory' => __DIR__ . '/Guzzle/Stream/PhpStreamRequestFactory.php',
		'DuplicatorPro\Guzzle\Stream\Stream' => __DIR__ . '/Guzzle/Stream/Stream.php',
		'DuplicatorPro\Guzzle\Stream\StreamInterface' => __DIR__ . '/Guzzle/Stream/StreamInterface.php',
		'DuplicatorPro\Guzzle\Stream\StreamRequestFactoryInterface' => __DIR__ . '/Guzzle/Stream/StreamRequestFactoryInterface.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher' => __DIR__ . '/Symfony/Component/EventDispatcher/ContainerAwareEventDispatcher.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass' => __DIR__ . '/Symfony/Component/EventDispatcher/DependencyInjection/RegisterListenersPass.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\Event' => __DIR__ . '/Symfony/Component/EventDispatcher/Event.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\EventDispatcher' => __DIR__ . '/Symfony/Component/EventDispatcher/EventDispatcher.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\EventDispatcherInterface' => __DIR__ . '/Symfony/Component/EventDispatcher/EventDispatcherInterface.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\EventSubscriberInterface' => __DIR__ . '/Symfony/Component/EventDispatcher/EventSubscriberInterface.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\GenericEvent' => __DIR__ . '/Symfony/Component/EventDispatcher/GenericEvent.php',
		'DuplicatorPro\Symfony\Component\EventDispatcher\ImmutableEventDispatcher' => __DIR__ . '/Symfony/Component/EventDispatcher/ImmutableEventDispatcher.php',		
	);
	
	function dup_pro_aws_autoload($class)
	{
		$dup_pro_aws_mapping = $GLOBALS['dup_pro_aws_mapping'];

		if (isset($dup_pro_aws_mapping[$class]))
		{
			// error_log($class.' -> '.$dup_pro_aws_mapping[$class]);
			require $dup_pro_aws_mapping[$class];
		}
	}

	spl_autoload_register('dup_pro_aws_autoload', true);
}