import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // Change this to your VPS/domain.
  static const String baseUrl = 'https://YOUR-DOMAIN.example/api';

  static Future<Map<String, dynamic>> login(String username, String password) async {
    final r = await http.post(
      Uri.parse('$baseUrl/login.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'username': username, 'password': password}),
    );
    if (r.statusCode != 200) throw Exception('Login failed');
    return jsonDecode(r.body) as Map<String, dynamic>;
  }

  static Future<Map<String, dynamic>> account(String token) async {
    final r = await http.get(
      Uri.parse('$baseUrl/account.php'),
      headers: {'Authorization': 'Bearer $token'},
    );
    if (r.statusCode != 200) throw Exception('Account request failed');
    return jsonDecode(r.body) as Map<String, dynamic>;
  }

  static Future<Map<String, dynamic>> transfer(
    String token, String to, num amount, String note,
  ) async {
    final r = await http.post(
      Uri.parse('$baseUrl/transfer.php'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({'to': to, 'amount': amount, 'note': note}),
    );
    if (r.statusCode != 200) throw Exception('Transfer failed');
    return jsonDecode(r.body) as Map<String, dynamic>;
  }
}
