<?php

require_once __DIR__ . '/../vendor/autoload.php'; // nạp Composer autoload

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class JwtProvider
{
  public static function generateToken(array $userInfo, string $secretSignature, string $tokenLife): string
  {
    try {
      $issuedAt = time();
      $expirationTime = $issuedAt + self::parseTokenLife($tokenLife); 

      $payload = array_merge($userInfo, [
        'iat' => $issuedAt,
        'exp' => $expirationTime,
      ]);

      return JWT::encode($payload, $secretSignature, 'HS256');
    } catch (Exception $e) {
      throw new Exception('Error generating token: ' . $e->getMessage());
    }
  }

  public static function verify(string $token, string $secretSignature)
  {
    try {
      return JWT::decode($token, new Key($secretSignature, 'HS256'));
    } catch (ExpiredException $e) {
      throw new Exception('Token has expired');
    } catch (SignatureInvalidException $e) {
      throw new Exception('Invalid token signature');
    } catch (BeforeValidException $e) {
      throw new Exception('Token is not yet valid');
    } catch (Exception $e) {
      throw new Exception('Error verifying token: ' . $e->getMessage());
    }
  }

  private static function parseTokenLife(string $tokenLife): int
  {
    if (is_numeric($tokenLife)) {
      return (int)$tokenLife;
    }

    if (preg_match('/^(\d+)h$/', $tokenLife, $matches)) {
      return (int)$matches[1] * 3600;
    }

    if (preg_match('/^(\d+)m$/', $tokenLife, $matches)) {
      return (int)$matches[1] * 60;
    }

    if (preg_match('/^(\d+) day([s?])$/', $tokenLife, $matches)) {
      return (int)$matches[1] * 3600 * 24;
    }

    throw new Exception('Invalid token life format');
  }
}
