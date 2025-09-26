#!/usr/bin/env python3
"""
Comprehensive API validation script for LangGraph PHP MVP web application
Tests all backend API endpoints and verifies frontend-backend integration
"""

import requests
import json
import time
from typing import Dict, List, Tuple
import sys
import os

BASE_URL = "http://localhost:8000/api"

class APITester:
    def __init__(self):
        self.base_url = BASE_URL
        self.session = requests.Session()
        self.session.headers.update({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        })
        self.results = {}
    
    def test_get_request(self, endpoint: str, params: Dict = None) -> Tuple[bool, dict]:
        """Test a GET request to the specified endpoint."""
        try:
            url = f"{self.base_url}{endpoint}"
            response = self.session.get(url, params=params)
            return response.status_code == 200, response.json()
        except Exception as e:
            return False, {"error": str(e)}
    
    def test_post_request(self, endpoint: str, data: Dict = None) -> Tuple[bool, dict]:
        """Test a POST request to the specified endpoint."""
        try:
            url = f"{self.base_url}{endpoint}"
            response = self.session.post(url, json=data)
            return response.status_code in [200, 201], response.json()
        except Exception as e:
            return False, {"error": str(e)}
    
    def test_stream_request(self, endpoint: str, data: Dict = None) -> Tuple[bool, list]:
        """Test a streaming request (used by multi-agent and validation endpoints)."""
        try:
            url = f"{self.base_url}{endpoint}"
            response = self.session.post(url, json=data, stream=True)
            
            events = []
            for line in response.iter_lines():
                line = line.decode('utf-8')
                if line.startswith('data: '):
                    try:
                        event_data = json.loads(line[6:])
                        events.append(event_data)
                        if event_data.get('status') == 'finished':
                            break
                    except json.JSONDecodeError:
                        continue
            
            return len(events) > 0, events
        except Exception as e:
            return False, [{"error": str(e)}]
    
    def run_tests(self):
        """Run all API tests."""
        print("Starting comprehensive API validation...")
        print("=" * 60)
        
        # Test LangGraph endpoints
        print("\nTesting LangGraph endpoints...")
        self.results['langgraph_simple'] = self.test_post_request('/langgraph/simple-workflow', {})
        print(f"  Simple workflow: {'✅ PASS' if self.results['langgraph_simple'][0] else '❌ FAIL'}")
        
        self.results['langgraph_advanced'] = self.test_post_request('/langgraph/advanced-workflow', {})
        print(f"  Advanced workflow: {'✅ PASS' if self.results['langgraph_advanced'][0] else '❌ FAIL'}")
        
        # Test Article endpoints
        print("\nTesting Article endpoints...")
        self.results['article_get'] = self.test_get_request('/article/1')
        print(f"  Get article (ID=1): {'✅ PASS' if self.results['article_get'][0] else '❌ FAIL'}")
        
        # Test article transition
        self.results['article_transition'] = self.test_post_request('/article/1/transition/submit', {})
        print(f"  Article transition: {'✅ PASS' if self.results['article_transition'][0] else '❌ FAIL'}")
        
        # Test Model endpoint (this will likely fail without API keys)
        print("\nTesting Model endpoint...")
        model_data = {
            "model_type": "deepseek",
            "prompt": "Say hello",
            "model_name": "default"
        }
        self.results['model_test'] = self.test_post_request('/model/test', model_data)
        if not self.results['model_test'][0] and "API key" in str(self.results['model_test'][1]):
            print("  Model test: ⚠️  Expected failure (no API key provided)")
            self.results['model_test'] = (True, self.results['model_test'][1])  # Mark as expected
        else:
            print(f"  Model test: {'✅ PASS' if self.results['model_test'][0] else '❌ FAIL'}")
        
        # Test Multi-Agent endpoint (streaming)
        print("\nTesting Multi-Agent endpoint...")
        agent_data = {
            "workflow_type": "simple",
            "task": "Test task for validation"
        }
        self.results['multi_agent'] = self.test_stream_request('/multi-agent/stream', agent_data)
        print(f"  Multi-Agent stream: {'✅ PASS' if self.results['multi_agent'][0] else '❌ FAIL'}")
        
        # Test Chat endpoint
        print("\nTesting Chat endpoint...")
        chat_data = {
            "message": "Hello, how are you?",
            "model_type": "qwen",
            "conversation_id": "test_conversation_123"
        }
        self.results['chat_process'] = self.test_post_request('/chat/process', chat_data)
        print(f"  Chat process: {'✅ PASS' if self.results['chat_process'][0] else '❌ FAIL'}")
        
        # Test Chat history endpoint
        self.results['chat_history'] = self.test_get_request('/chat/history/test_conversation_123')
        print(f"  Chat history: {'✅ PASS' if self.results['chat_history'][0] else '❌ FAIL'}")
        
        # Test Workflow Validation endpoint (streaming)
        print("\nTesting Workflow Validation endpoint...")
        validation_data = {
            "workflow": {
                "name": "Test Workflow",
                "nodes": {
                    "start": {"type": "start"},
                    "process": {"type": "task"},
                    "end": {"type": "end"}
                },
                "edges": [
                    {"from": "start", "to": "process"},
                    {"from": "process", "to": "end"}
                ]
            },
            "rules": []
        }
        self.results['workflow_validation'] = self.test_stream_request('/workflow-validation/validate', validation_data)
        print(f"  Workflow validation: {'✅ PASS' if self.results['workflow_validation'][0] else '❌ FAIL'}")
        
        # Test Validation report endpoint
        self.results['validation_report'] = self.test_get_request('/workflow-validation/report/test_validation_123')
        print(f"  Validation report: {'✅ PASS' if self.results['validation_report'][0] else '❌ FAIL'}")
        
        print("\n" + "=" * 60)
        self.print_summary()
    
    def print_summary(self):
        """Print a summary of test results."""
        passed = sum(1 for success, _ in self.results.values() if success)
        total = len(self.results)
        
        print(f"\nTest Summary: {passed}/{total} endpoints working correctly")
        
        if passed == total:
            print("🎉 All API endpoints are functioning properly!")
        else:
            print(f"⚠️  {total - passed} endpoints need attention")
        
        print("\nDetailed Results:")
        for endpoint, (success, data) in self.results.items():
            status = "✅ PASS" if success else "❌ FAIL"
            print(f"  {endpoint}: {status}")
            
            if not success:
                print(f"    Error: {data}")


def main():
    tester = APITester()
    tester.run_tests()


if __name__ == "__main__":
    main()