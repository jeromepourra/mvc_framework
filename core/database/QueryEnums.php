<?php

namespace core\database;

enum EQueryStatement: string {
	case SELECT = "SELECT";
	case UPDATE = "UPDATE";
	case INSERT = "INSERT INTO";
	case DELETE = "DELETE";
}

enum EQueryClause: string {
	
	case FROM = "FROM";
	case DISTINCT = "DISTINCT";

	case WHERE = "WHERE";
	case LIMIT = "LIMIT";
	case OFFSET = "OFFSET";

	case ORDER_BY = "ORDER BY";
	case GROUP_BY = "GROUP BY";

	case JOIN = "JOIN";
	case ON = "ON";

	case SET = "SET";
	case VALUES = "VALUES";

}

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

enum EQueryJoin: string {
	case INNER = "INNER";
	case LEFT = "LEFT OUTER";
	case RIGHT = "RIGHT OUTER";
}