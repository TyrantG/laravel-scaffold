<?php

namespace TyrantG\LaravelScaffold\Enums;

enum ResponseCodeEnum: int
{
    case RESPONSE_SUCCESS = 0;
    case BAD_REQUEST = 4000;
    case AUTHENTICATION_FAILED = 4001;
    case ROUTE_NOT_FOUND = 4040;
    case MODEL_NOT_FOUND = 4041;
    case REQUEST_TIMESTAMP_EXCEED = 4101;
    case REQUEST_SIGNATURE_ERROR = 4103;
    case DATA_HAS_EXISTS = 4433;
    case SERVER_ERROR = 5000;
}
