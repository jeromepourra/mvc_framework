<?php

enum EQueryDoor: string {
	case AND = "AND";
	case OR = "OR";
}

enum EQueryOperator: string {
	case EQUAL = "=";
	case NOT_EQUAL = "!=";
	case LESS = "<";
	case LESS_OR_EQUAL = "<=";
	case GREATHER = ">";
	case GREATHER_OR_EQUAL = ">=";
	case LIKE = "LIKE";
	case NOT_LIKE = "NOT LIKE";
	case IS_NULL = "IS NULL";
	case IS_NOT_NULL = "IS NOT NULL";
}