<?php

namespace controller;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Throwable;

abstract class BaseController
{
    protected string $viewPath;

    public function __construct()
    {
        $this->viewPath = dirname(__DIR__) . '/view';
    }

    protected function render(string $view, array $data = []): void
    {
        $pageTitle = $data['pageTitle'] ?? 'Gerenciamento de Projetos';
        $currentRoute = $_GET['route'] ?? 'dashboard';
        $flash = $this->consumeFlash();

        extract($data, EXTR_SKIP);

        require $this->viewPath . '/layout/header.php';
        require $this->viewPath . '/' . $view . '.php';
        require $this->viewPath . '/layout/footer.php';
    }

    protected function redirect(string $route = 'dashboard', array $params = []): never
    {
        $url = $this->url($route, $params);
        header('Location: ' . $url);
        exit;
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new InvalidArgumentException('Requisicao invalida para esta acao.');
        }
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    protected function consumeFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function url(string $route = 'dashboard', array $params = []): string
    {
        $query = array_merge(['route' => $route], $params);

        return 'index.php?' . http_build_query($query);
    }

    public function e(mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            $value = $value->format('d/m/Y');
        }

        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public function isActive(string $routePrefix, string $currentRoute): string
    {
        return str_starts_with($currentRoute, $routePrefix) ? 'active' : '';
    }

    public function formatDate(?DateTimeInterface $date): string
    {
        return $date ? $date->format('d/m/Y') : '-';
    }

    public function inputDate(?DateTimeInterface $date): string
    {
        return $date ? $date->format('Y-m-d') : '';
    }

    protected function textInput(string $key, bool $required = false): ?string
    {
        $value = trim((string) ($_POST[$key] ?? ''));

        if ($required && $value === '') {
            throw new InvalidArgumentException('Preencha todos os campos obrigatorios.');
        }

        return $value === '' ? null : $value;
    }

    protected function intInput(string $key, int $default = 0, ?int $min = null, ?int $max = null): int
    {
        $raw = trim((string) ($_POST[$key] ?? ''));

        if ($raw === '') {
            $value = $default;
        } else {
            $value = filter_var($raw, FILTER_VALIDATE_INT);

            if ($value === false) {
                throw new InvalidArgumentException('Informe um numero inteiro valido.');
            }
        }

        if ($min !== null && $value < $min) {
            throw new InvalidArgumentException('Informe um valor maior ou igual a ' . $min . '.');
        }

        if ($max !== null && $value > $max) {
            throw new InvalidArgumentException('Informe um valor menor ou igual a ' . $max . '.');
        }

        return $value;
    }

    protected function floatInput(string $key, float $default = 0.0, ?float $min = null): float
    {
        $raw = str_replace(',', '.', trim((string) ($_POST[$key] ?? '')));

        if ($raw === '') {
            $value = $default;
        } elseif (is_numeric($raw)) {
            $value = (float) $raw;
        } else {
            throw new InvalidArgumentException('Informe um valor numerico valido.');
        }

        if ($min !== null && $value < $min) {
            throw new InvalidArgumentException('Informe um valor maior ou igual a ' . $min . '.');
        }

        return $value;
    }

    protected function dateInput(string $key): ?DateTime
    {
        $value = trim((string) ($_POST[$key] ?? ''));

        if ($value === '') {
            return null;
        }

        $date = DateTime::createFromFormat('!Y-m-d', $value);
        $errors = DateTime::getLastErrors();

        if (!$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            throw new InvalidArgumentException('Informe uma data valida.');
        }

        $date->setTime(0, 0);

        return $date;
    }

    protected function findById(array $items, int $id): ?object
    {
        foreach ($items as $item) {
            if (method_exists($item, 'getId') && $item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }

    public function selected(string $expected, ?string $current): string
    {
        return $expected === $current ? 'selected' : '';
    }

    public function checked(bool $value): string
    {
        return $value ? 'checked' : '';
    }

    protected function safeList(string $label, callable $callback, ?string &$error = null): array
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            $this->logException($e);
            $error = 'Nao foi possivel carregar ' . $label . '. Verifique a conexao com o banco de dados.';
            return [];
        }
    }

    protected function errorMessage(Throwable $e, string $fallback): string
    {
        $this->logException($e);

        if ($e instanceof InvalidArgumentException) {
            return $e->getMessage();
        }

        if ($this->isDevelopmentEnvironment()) {
            return $fallback . ' Detalhes: ' . $this->exceptionSummary($e);
        }

        return $fallback;
    }

    protected function logException(Throwable $e): void
    {
        error_log(sprintf(
            '[GerenciamentoProjetos] %s: %s em %s:%d',
            $e::class,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));

        if ($e->getPrevious()) {
            $this->logException($e->getPrevious());
        }
    }

    private function isDevelopmentEnvironment(): bool
    {
        $env = strtolower((string) ($_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: ''));

        if (in_array($env, ['local', 'dev', 'development', 'test'], true)) {
            return true;
        }

        if (in_array($env, ['prod', 'production'], true)) {
            return false;
        }

        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? ''));

        return str_starts_with($host, 'localhost')
            || str_starts_with($host, '127.0.0.1')
            || str_starts_with($host, '[::1]');
    }

    private function exceptionSummary(Throwable $e): string
    {
        $messages = [];
        $current = $e;

        while ($current) {
            $messages[] = $current::class . ': ' . $current->getMessage();
            $current = $current->getPrevious();
        }

        return implode(' | ', $messages);
    }
}
