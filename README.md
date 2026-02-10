# TaskManager

Aplikacja do zarządzania zadaniami zbudowana w oparciu o Symfony 7, GraphQL i zasady Domain-Driven Design (DDD). Projekt wykorzystuje Event Sourcing do śledzenia historii zmian zadań oraz integruje się z zewnętrznym API (JSONPlaceholder).

## Główne Funkcjonalności

*   **Zarządzanie Użytkownikami**: 
    *   Pobieranie i synchronizacja użytkowników z zewnętrznego API (JSONPlaceholder).
    *   Logowanie i pobieranie danych o zalogowanym użytkowniku.
*   **Zarządzanie Zadaniami**:
    *   Tworzenie zadań z przypisaniem do użytkownika.
    *   Zmiana statusu zadań zgodnie ze zdefiniowaną strategią workflow (To Do -> In Progress -> Done).
    *   Lista zadań przypisanych do konkretnego użytkownika.
    *   Pełna lista zadań dostępna dla administratora (użytkownik o ID: 1).
*   **Event Sourcing**: 
    *   Historia każdej operacji na zadaniu (utworzenie, zmiana statusu) zapisywana w Event Store.
*   **GraphQL API**:
    *   Pełna obsługa zapytań przez endpoint GraphQL.

## Technologie

*   **PHP 8.3+**
*   **Symfony 7.4**
*   **OverblogGraphQLBundle** – obsługa GraphQL API.
*   **Doctrine ORM** – warstwa persistencji.
*   **Symfony Messenger** – obsługa komend i zdarzeń (Event Sourcing).
*   **PHPUnit** – testy jednostkowe.
*   **DDEV** – środowisko uruchomieniowe (Docker).

## Architektura (DDD)

Projekt został podzielony na warstwy zgodnie z zasadami Domain-Driven Design:

1.  **Domain (Warstwa Domenowa)**:
    *   Zawiera logikę biznesową, Agregaty (`User`, `Task`), Obiekty Wartości (`TaskName`, `TaskStatus`) oraz Interfejsy Strategii.
    *   Wzorzec **Factory**: Wykorzystanie metod statycznych (Named Constructors) w Agregatach (`create`, `reconstitute`) do bezpiecznego tworzenia i odtwarzania obiektów domeny bez bezpośredniego użycia operatora `new`.
    *   Wzorzec **Strategy**: `TaskStatusTransitionStrategy` definiuje zasady przechodzenia między statusami zadań.
2.  **Application (Warstwa Aplikacyjna)**:
    *   Zawiera Handlery Komend (`TaskCreateHandler`, `TaskUpdateHandler`, `UsersImportHandler`), które koordynują zadania i delegują logikę do domeny.
3.  **Infrastructure (Warstwa Infrastruktury)**:
    *   Implementacja repozytoriów, integracja z API zewnętrznym (`JsonPlaceholderClient`), mapowanie Doctrine oraz resolver GraphQL.

## Instalacja i Uruchomienie

### Wymagania
*   [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)
*   Docker

### Kroki instalacji
1.  Sklonuj repozytorium.
2.  Uruchom środowisko DDEV:
    ```bash
    ddev start
    ```
3.  Zainstaluj zależności PHP:
    ```bash
    ddev composer install
    ```
4.  Uruchom migracje bazy danych:
    ```bash
    ddev php bin/console doctrine:migrations:migrate --no-interaction
    ```
5.  Zaimportuj użytkowników z JSONPlaceholder za pomocą mutacji GraphQL:
    ```graphql
    mutation {
      importUsers
    }
    ```

## GraphQL API

Endpoint GraphQL dostępny jest pod adresem: `https://taskmanager.ddev.site/graphql`

### Przykładowe operacje:

**Import użytkowników:**
```graphql
mutation {
  importUsers
}
```

**Logowanie (wymagane do operacji na zadaniach):**
```graphql
mutation {
  login(userId: 1)
}
```

**Pobieranie zadań użytkownika:**
```graphql
query {
  tasksByUser(userId: 1) {
    id
    name
    status
  }
}
```

## Testy

W projekcie znajdują się testy jednostkowe pokrywające kluczową logikę domenową oraz resolvery.

Aby uruchomić testy:
```bash
ddev php .\vendor\bin\phpunit
```

Główne zestawy testów:
*   `tests/Domain/Strategy/DefaultTaskWorkflowStrategyTest.php` – testy strategii zmiany statusów.
*   `tests/Infrastructure/GraphQL/Resolver/QueryResolverTest.php` – testy resolvera GraphQL (z wykorzystaniem mocków/stubów).

## Zastosowane Wzorce Projektowe

*   **Factory Pattern**: Zastosowany w modelach domenowych (`Task`, `User`) do enkapsulacji logiki tworzenia obiektów, zapewniając spójność danych od momentu inicjalizacji.
*   **Strategy Pattern**: Wykorzystany w `DefaultTaskWorkflowStrategy`, co pozwala na łatwą zmianę lub rozszerzenie zasad workflow zadań bez modyfikacji klasy `Task`.
