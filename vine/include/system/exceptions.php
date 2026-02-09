<?php

/**
 * FRAMEWORK DEPENDENCY - DO NOT MODIFY!
 * ---
 * These are the various exceptions that can get thrown throughout the Vine Framework.
 * They use descriptive naming conventions. They don't contain underscores in their
 * naming, because the Vine reserves underscores in class names as directory definitions.
 */

/**
 * Base exception for file related exceptions. Thrown when a file or directory cannot be
 * used.
 */
class VineFileException extends Exception { }

/**
 * Thrown when an expected file or directory is missing or not found.
 */
class VineMissingFileException extends VineFileException { }

/**
 * Thrown when a file or directory is found, but does not contain the expected data.
 */
class VineBadFileException extends VineFileException { }

/**
 * Thrown when a file or directory exists, but lacks permissions necessary in order to
 * read or open it.
 */
class VinePermissionsException extends VineFileException { }

/**
 * Thrown when an undeclared property is accessed, when an undeclared property is set, or
 * when a dependency property is overwritten.
 */
class VinePropertyException extends Exception { }

/**
 * Thrown when a variable, property, or array, is not valid or not provided.
 */
class VineDataException extends Exception { }

/**
 * Thrown when a variable, property, array, or resource, contains an invalid data type,
 * or when a data type is expected, but simply not provided.
 */
class VineBadTypeException extends VineDataException { }

/**
 * Thrown when an array is not valid or is missing, such as when a required key or value
 * is not present, or when an array is expected, but not provided.
 */
class VineBadArrayException extends VineDataException { }

/**
 * Thrown when an object is not valid or is missing, such as when a required property or
 * method is not present, or when an object is expected, but not provided.
 */
class VineBadObjectException extends VineDataException { }

/**
 * Thrown when a string, integer, or float does not contain an expected value or range.
 */
class VineBadValueException extends VineDataException { }

/**
 * Base exception for interface related exceptions. Thrown when an interface specific
 * error occurs.
 */
class VineInterfaceException extends Exception { }

/**
 * Thrown when an inteface method is called but has not been implemented.
 */
class VineMethodNotImplementedException extends VineInterfaceException { }
