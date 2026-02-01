# API Documentation

## Authentication

### User Authentication (Sanctum)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/login` | POST | Authenticate user and return token |
| `/api/v1/register` | POST | Register a new member |
| `/api/v1/logout` | POST | Revoke current token |
| `/api/v1/me` | GET | Get current authenticated user details |

## Member Portal API

### Catalog

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/catalog` | GET | Browse resources with filters and search |
| `/api/v1/catalog/{resource}` | GET | Get detailed resource information |
| `/api/v1/categories` | GET | List all resource categories |
| `/api/v1/authors` | GET | List all authors |

### Member Activity (Auth Required)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/profile` | GET/PUT | View/Update personal profile |
| `/api/v1/my-loans` | GET | List current and past loans |
| `/api/v1/loans/{loan}/renew` | POST | Request a loan renewal |
| `/api/v1/my-reservations` | GET | List current hold requests |
| `/api/v1/reservations` | POST | Create a new reservation |
| `/api/v1/reservations/{reservation}` | DELETE | Cancel a reservation |
| `/api/v1/my-fines` | GET | List outstanding fines and payment history |

## Admin API (Internal Use)

While the admin panel uses server-side Blade templates, certain dynamic features (like charts or live search) use internal API endpoints:

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/admin/api/stats` | GET | Get real-time dashboard statistics |
| `/admin/api/scan/process` | POST | Process barcode/QR code scans |

## Standards

- **Format**: JSON
- **Version**: `v1`
- **Rate Limit**: 60 requests/minute
- **Error Format**:
```json
{
    "message": "Error description",
    "errors": { "field": ["detail"] }
}
```
