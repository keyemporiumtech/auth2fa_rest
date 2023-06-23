<?php

/**
 * Tipologie di query Like
 * 
 * @author Giuseppe Sassone
 *
 */
class EnumQueryLike {
	const LEFT= 1; // %VAL
	const RIGHT= 2; // VAL%
	const LEFT_RIGHT= 3; // %VAL%
	const PRECISION= 4; // VAL
}