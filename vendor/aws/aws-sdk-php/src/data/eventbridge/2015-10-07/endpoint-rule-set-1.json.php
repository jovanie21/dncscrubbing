<?php
// This file was auto-generated from sdk-root/src/data/eventbridge/2015-10-07/endpoint-rule-set-1.json
return [ 'version' => '1.0', 'parameters' => [ 'Region' => [ 'builtIn' => 'AWS::Region', 'required' => false, 'documentation' => 'The AWS region used to dispatch the request.', 'type' => 'String', ], 'UseDualStack' => [ 'builtIn' => 'AWS::UseDualStack', 'required' => true, 'default' => false, 'documentation' => 'When true, use the dual-stack endpoint. If the configured endpoint does not support dual-stack, dispatching the request MAY return an error.', 'type' => 'Boolean', ], 'UseFIPS' => [ 'builtIn' => 'AWS::UseFIPS', 'required' => true, 'default' => false, 'documentation' => 'When true, send this request to the FIPS-compliant regional endpoint. If the configured endpoint does not have a FIPS compliant endpoint, dispatching the request will return an error.', 'type' => 'Boolean', ], 'Endpoint' => [ 'builtIn' => 'SDK::Endpoint', 'required' => false, 'documentation' => 'Override the endpoint used to send this request', 'type' => 'String', ], 'EndpointId' => [ 'required' => false, 'documentation' => 'Operation parameter for EndpointId', 'type' => 'String', ], ], 'rules' => [ [ 'conditions' => [ [ 'fn' => 'isSet', 'argv' => [ [ 'ref' => 'EndpointId', ], ], ], [ 'fn' => 'isSet', 'argv' => [ [ 'ref' => 'Region', ], ], ], [ 'fn' => 'aws.partition', 'argv' => [ [ 'ref' => 'Region', ], ], 'assign' => 'PartitionResult', ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'isValidHostLabel', 'argv' => [ [ 'ref' => 'EndpointId', ], true, ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseFIPS', ], false, ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'isSet', 'argv' => [ [ 'ref' => 'Endpoint', ], ], ], ], 'endpoint' => [ 'url' => [ 'ref' => 'Endpoint', ], 'properties' => [ 'authSchemes' => [ [ 'name' => 'sigv4a', 'signingName' => 'events', 'signingRegionSet' => [ '*', ], ], ], ], 'headers' => [], ], 'type' => 'endpoint', ], [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseDualStack', ], true, ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ true, [ 'fn' => 'getAttr', 'argv' => [ [ 'ref' => 'PartitionResult', ], 'supportsDualStack', ], ], ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [], 'endpoint' => [ 'url' => 'https://{EndpointId}.endpoint.events.{PartitionResult#dualStackDnsSuffix}', 'properties' => [ 'authSchemes' => [ [ 'name' => 'sigv4a', 'signingName' => 'events', 'signingRegionSet' => [ '*', ], ], ], ], 'headers' => [], ], 'type' => 'endpoint', ], ], ], [ 'conditions' => [], 'error' => 'DualStack is enabled but this partition does not support DualStack', 'type' => 'error', ], ], ], [ 'conditions' => [], 'endpoint' => [ 'url' => 'https://{EndpointId}.endpoint.events.{PartitionResult#dnsSuffix}', 'properties' => [ 'authSchemes' => [ [ 'name' => 'sigv4a', 'signingName' => 'events', 'signingRegionSet' => [ '*', ], ], ], ], 'headers' => [], ], 'type' => 'endpoint', ], ], ], [ 'conditions' => [], 'error' => 'Invalid Configuration: FIPS is not supported with EventBridge multi-region endpoints.', 'type' => 'error', ], ], ], [ 'conditions' => [], 'error' => 'EndpointId must be a valid host label.', 'type' => 'error', ], ], ], [ 'conditions' => [ [ 'fn' => 'isSet', 'argv' => [ [ 'ref' => 'Endpoint', ], ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseFIPS', ], true, ], ], ], 'error' => 'Invalid Configuration: FIPS and custom endpoint are not supported', 'type' => 'error', ], [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseDualStack', ], true, ], ], ], 'error' => 'Invalid Configuration: Dualstack and custom endpoint are not supported', 'type' => 'error', ], [ 'conditions' => [], 'endpoint' => [ 'url' => [ 'ref' => 'Endpoint', ], 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], ], ], [ 'conditions' => [ [ 'fn' => 'isSet', 'argv' => [ [ 'ref' => 'Region', ], ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'aws.partition', 'argv' => [ [ 'ref' => 'Region', ], ], 'assign' => 'PartitionResult', ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseFIPS', ], true, ], ], [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseDualStack', ], true, ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ true, [ 'fn' => 'getAttr', 'argv' => [ [ 'ref' => 'PartitionResult', ], 'supportsFIPS', ], ], ], ], [ 'fn' => 'booleanEquals', 'argv' => [ true, [ 'fn' => 'getAttr', 'argv' => [ [ 'ref' => 'PartitionResult', ], 'supportsDualStack', ], ], ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [], 'endpoint' => [ 'url' => 'https://events-fips.{Region}.{PartitionResult#dualStackDnsSuffix}', 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], ], ], [ 'conditions' => [], 'error' => 'FIPS and DualStack are enabled, but this partition does not support one or both', 'type' => 'error', ], ], ], [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseFIPS', ], true, ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ true, [ 'fn' => 'getAttr', 'argv' => [ [ 'ref' => 'PartitionResult', ], 'supportsFIPS', ], ], ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'stringEquals', 'argv' => [ [ 'ref' => 'Region', ], 'us-gov-east-1', ], ], ], 'endpoint' => [ 'url' => 'https://events.us-gov-east-1.amazonaws.com', 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], [ 'conditions' => [ [ 'fn' => 'stringEquals', 'argv' => [ [ 'ref' => 'Region', ], 'us-gov-west-1', ], ], ], 'endpoint' => [ 'url' => 'https://events.us-gov-west-1.amazonaws.com', 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], [ 'conditions' => [], 'endpoint' => [ 'url' => 'https://events-fips.{Region}.{PartitionResult#dnsSuffix}', 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], ], ], [ 'conditions' => [], 'error' => 'FIPS is enabled but this partition does not support FIPS', 'type' => 'error', ], ], ], [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ [ 'ref' => 'UseDualStack', ], true, ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [ [ 'fn' => 'booleanEquals', 'argv' => [ true, [ 'fn' => 'getAttr', 'argv' => [ [ 'ref' => 'PartitionResult', ], 'supportsDualStack', ], ], ], ], ], 'type' => 'tree', 'rules' => [ [ 'conditions' => [], 'endpoint' => [ 'url' => 'https://events.{Region}.{PartitionResult#dualStackDnsSuffix}', 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], ], ], [ 'conditions' => [], 'error' => 'DualStack is enabled but this partition does not support DualStack', 'type' => 'error', ], ], ], [ 'conditions' => [], 'endpoint' => [ 'url' => 'https://events.{Region}.{PartitionResult#dnsSuffix}', 'properties' => [], 'headers' => [], ], 'type' => 'endpoint', ], ], ], ], ], [ 'conditions' => [], 'error' => 'Invalid Configuration: Missing Region', 'type' => 'error', ], ],];
