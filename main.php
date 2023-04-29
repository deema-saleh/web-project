.MODEL SMALL
.STACK 100H

.DATA
num1 DB ?
num2 DB ?
result DB ?
operation DB ?
flagRegister DW ?

prompt1 DB 'Enter the first number (hex format): $'
prompt2 DB 'Enter the second number (hex format): $'
prompt3 DB 'Enter the operation (+, -, i, &): $'
invalidMsg DB 'Invalid operation. Please enter +, -, i, or &: $'
resultMsg DB 'The result is: $'
flagMsg DB 'The flag register is: $'
hexFormat DB '%02XH'
decimalFormat DB '%d'

.CODE
MAIN PROC

; Display prompt1 and read num1
MOV AH, 9
LEA DX, prompt1
INT 21H

; Read and validate num1 input
CALL READ_HEX_INPUT
MOV num1, AL

; Display prompt2 and read num2
MOV AH, 9
LEA DX, prompt2
INT 21H

; Read and validate num2 input
CALL READ_HEX_INPUT
MOV num2, AL

; Display prompt3 and read operation
MOV AH, 9
LEA DX, prompt3
INT 21H

; Read and validate operation input
CALL READ_OPERATION_INPUT
MOV operation, AL

; Perform the operation on num1 and num2
CALL DO_OPERATION
MOV result, AL
MOV flagRegister, FLAGS

; Display the result and flag bits
MOV AH, 9
LEA DX, resultMsg
INT 21H

MOV AX, result
PUSH AX
LEA DX, decimalFormat
MOV AH, 0AH
INT 21H

MOV AH, 9
LEA DX, hexFormat
INT 21H

MOV AH, 9
LEA DX, flagMsg
INT 21H

MOV AX, flagRegister
PUSH AX
LEA DX, hexFormat
MOV AH, 9
INT 21H

MOV AH, 4CH
INT 21H

MAIN ENDP

READ_HEX_INPUT PROC
  ; Reads a byte-sized input in hex format from the console.
  ; Returns the input value in AL.

  ; Initialize variables
  MOV BL, 0 ; Stores the first hex digit
  MOV CL, 0 ; Stores the second hex digit

  ; Read first hex digit
  MOV AH, 1 ; Function to read a character from the console
  INT 21H
  CMP AL, '0'
  JB INVALID_HEX_INPUT
  CMP AL, '9'
  JA VALID_FIRST_DIGIT
  SUB AL, '0'
  JMP STORE_FIRST_DIGIT

VALID_FIRST_DIGIT:
  CMP AL, 'A'
  JB INVALID_HEX_INPUT
  CMP AL, 'F'
  JA INVALID_HEX_INPUT
  SUB AL, 'A' - 10

STORE_FIRST_DIGIT:
  MOV BL, AL
  MOV AH, 1 ; Function to read a character from the console
  INT 21H

  ; Read second hex digit
  CMP AL, '0'
  JB INVALID_HEX_INPUT
  CMP AL, '9'
  JA VALID_SECOND_DIGIT
  SUB AL, '0'
  JMP STORE_SECOND_DIGIT

VALID_SECOND_DIGIT:
  CMP AL, 'A'
  JB INVALID_HEX_INPUT
  CMP AL, 'F'
