#!/usr/bin/env python3
"""
Comprehensive test suite for the core LangGraph PHP MVP features:
- LangGraph Simple Workflow
- LangGraph Advanced Workflow
- Multi-Agent System
- Workflow Validation

This test suite validates all core features with multiple scenarios and edge cases.
"""

import requests
import json
import time
from typing import Dict, List, Tuple, Any
import sys
import os


BASE_URL = "http://localhost:8000/api"

class CoreFeaturesTester:
    def __init__(self):
        self.base_url = BASE_URL
        self.session = requests.Session()
        self.session.headers.update({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        })
        self.results = {}
        self.detailed_results = {}

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

            # Check if response is an error (not a stream)
            if response.status_code != 200:
                return False, [{"error": response.json(), "status_code": response.status_code}]

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

    def run_comprehensive_tests(self):
        """Run comprehensive tests for all core features."""
        print("Starting comprehensive tests for core LangGraph features...")
        print("=" * 80)

        # Test LangGraph Simple Workflow (multiple scenarios)
        self.test_langgraph_simple_workflow()
        
        # Test LangGraph Advanced Workflow (multiple scenarios)
        self.test_langgraph_advanced_workflow()
        
        # Test Multi-Agent System (multiple scenarios)
        self.test_multi_agent_system()
        
        # Test Workflow Validation (multiple scenarios)
        self.test_workflow_validation()

        print("\n" + "=" * 80)
        self.print_summary()
        return self.results

    def test_langgraph_simple_workflow(self):
        """Test LangGraph Simple Workflow with multiple scenarios."""
        print("\n🧪 Testing LangGraph Simple Workflow...")
        
        # Basic execution test
        print("  - Basic execution test...")
        result_basic = self.test_post_request('/langgraph/simple-workflow', {})
        self.results['langgraph_simple_basic'] = result_basic
        print(f"    Basic execution: {'✅ PASS' if result_basic[0] else '❌ FAIL'}")
        
        # Verify response structure
        if result_basic[0]:
            data = result_basic[1]['data']
            expected_keys = {'step', 'message'}
            actual_keys = set(data.keys())
            has_expected_keys = expected_keys.issubset(actual_keys)
            self.results['langgraph_simple_structure'] = (has_expected_keys, data if has_expected_keys else {'error': f'Missing keys. Expected: {expected_keys}, Got: {actual_keys}'})
            print(f"    Response structure: {'✅ PASS' if has_expected_keys else '❌ FAIL'}")
        else:
            self.results['langgraph_simple_structure'] = (False, result_basic[1])
            print(f"    Response structure: ❌ FAIL - Base test failed")
        
        # Error handling test (test with invalid data)
        print("  - Error handling test...")
        result_error = self.test_post_request('/langgraph/simple-workflow', {"invalid": "data"})
        # Even with invalid data, the simple workflow should still work
        self.results['langgraph_simple_error_handling'] = result_error
        print(f"    Error handling: {'✅ PASS' if result_error[0] else '❌ FAIL'}")

    def test_langgraph_advanced_workflow(self):
        """Test LangGraph Advanced Workflow with multiple scenarios."""
        print("\n🧪 Testing LangGraph Advanced Workflow...")
        
        # Basic execution test
        print("  - Basic execution test...")
        result_basic = self.test_post_request('/langgraph/advanced-workflow', {})
        self.results['langgraph_advanced_basic'] = result_basic
        print(f"    Basic execution: {'✅ PASS' if result_basic[0] else '❌ FAIL'}")
        
        # Execution with custom task
        print("  - Execution with custom task...")
        result_custom = self.test_post_request('/langgraph/advanced-workflow', {
            'task': 'Analyze market trends for Q4 2024'
        })
        self.results['langgraph_advanced_custom_task'] = result_custom
        print(f"    Custom task: {'✅ PASS' if result_custom[0] else '❌ FAIL'}")
        
        # Verify response structure for custom task
        if result_custom[0]:
            data = result_custom[1]['data']
            expected_keys = {'task', 'messages', 'step', 'processing_result', 'final_result'}
            actual_keys = set(data.keys())
            has_expected_keys = expected_keys.issubset(actual_keys)
            self.results['langgraph_advanced_structure'] = (has_expected_keys, data if has_expected_keys else {'error': f'Missing keys. Expected: {expected_keys}, Got: {actual_keys}'})
            print(f"    Response structure: {'✅ PASS' if has_expected_keys else '❌ FAIL'}")
        else:
            self.results['langgraph_advanced_structure'] = (False, result_custom[1])
            print(f"    Response structure: ❌ FAIL - Base test failed")
        
        # Error handling test
        print("  - Error handling test...")
        result_error = self.test_post_request('/langgraph/advanced-workflow', {"invalid": "data"})
        # Advanced workflow should handle invalid data gracefully
        self.results['langgraph_advanced_error_handling'] = result_error
        print(f"    Error handling: {'✅ PASS' if result_error[0] else '❌ FAIL'}")

    def test_multi_agent_system(self):
        """Test Multi-Agent System with multiple scenarios."""
        print("\n🧪 Testing Multi-Agent System...")
        
        # Simple workflow test
        print("  - Simple workflow test...")
        result_simple = self.test_stream_request('/multi-agent/stream', {
            'workflow_type': 'simple',
            'task': 'Research the benefits of renewable energy'
        })
        self.results['multi_agent_simple'] = result_simple
        print(f"    Simple workflow: {'✅ PASS' if result_simple[0] else '❌ FAIL'}")
        
        # Verify stream events for simple workflow
        if result_simple[0] and len(result_simple[1]) > 0:
            events = result_simple[1]
            statuses = [event.get('status') for event in events]
            required_statuses = {'started', 'processing', 'completed', 'finished'}
            has_required_statuses = required_statuses.issubset(set(statuses))
            self.results['multi_agent_simple_stream_format'] = (has_required_statuses, events)
            print(f"    Stream format: {'✅ PASS' if has_required_statuses else '❌ FAIL'}")
        else:
            self.results['multi_agent_simple_stream_format'] = (False, result_simple[1])
            print(f"    Stream format: ❌ FAIL - Base test failed")
        
        # Advanced workflow test
        print("  - Advanced workflow test...")
        result_advanced = self.test_stream_request('/multi-agent/stream', {
            'workflow_type': 'advanced',
            'task': 'Analyze market trends for renewable energy sector in 2025'
        })
        self.results['multi_agent_advanced'] = result_advanced
        print(f"    Advanced workflow: {'✅ PASS' if result_advanced[0] else '❌ FAIL'}")
        
        # Verify stream events for advanced workflow
        if result_advanced[0] and len(result_advanced[1]) > 0:
            events = result_advanced[1]
            statuses = [event.get('status') for event in events]
            required_statuses = {'started', 'processing', 'completed', 'finished'}
            has_required_statuses = required_statuses.issubset(set(statuses))
            self.results['multi_agent_advanced_stream_format'] = (has_required_statuses, events)
            print(f"    Stream format: {'✅ PASS' if has_required_statuses else '❌ FAIL'}")
        else:
            self.results['multi_agent_advanced_stream_format'] = (False, result_advanced[1])
            print(f"    Stream format: ❌ FAIL - Base test failed")
        
        # Invalid workflow type test
        print("  - Invalid workflow type test...")
        result_invalid = self.test_stream_request('/multi-agent/stream', {
            'workflow_type': 'invalid_type',
            'task': 'Test task'
        })
        
        # This should return an error (400), which means the first element of result_invalid should be False
        # But for this test, getting an error is the expected behavior
        # Let's check if we got the expected error response
        is_expected_error = (
            not result_invalid[0] and  # Expected False for success flag
            len(result_invalid[1]) > 0 and  # Has error data
            isinstance(result_invalid[1][0], dict) and
            'error' in result_invalid[1][0] and
            'Invalid workflow type selected' in str(result_invalid[1][0]['error'])
        )
        
        # For this particular test, we consider it passed if we got the expected error
        self.results['multi_agent_invalid_type'] = (is_expected_error, result_invalid[1])
        print(f"    Invalid workflow type: {'✅ PASS (expected error received)' if is_expected_error else '❌ FAIL (wrong response)'}")
        
        # Missing task test (should still work with default task)
        print("  - Missing task test (with default task)...")
        result_missing_task = self.test_stream_request('/multi-agent/stream', {
            'workflow_type': 'simple'
            # Missing task - will use default
        })
        self.results['multi_agent_missing_task'] = result_missing_task
        print(f"    With default task: {'✅ PASS' if result_missing_task[0] else '❌ FAIL'}")

    def test_workflow_validation(self):
        """Test Workflow Validation with multiple scenarios."""
        print("\n🧪 Testing Workflow Validation...")
        
        # Valid workflow test
        print("  - Valid workflow test...")
        valid_workflow = {
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
        result_valid = self.test_stream_request('/workflow-validation/validate', valid_workflow)
        self.results['workflow_validation_valid'] = result_valid
        print(f"    Valid workflow: {'✅ PASS' if result_valid[0] else '❌ FAIL'}")
        
        # Verify stream events for valid workflow
        if result_valid[0] and len(result_valid[1]) > 0:
            events = result_valid[1]
            statuses = [event.get('status') for event in events]
            required_statuses = {'started', 'input_validation', 'structure_validation', 'rule_validation', 'execution_simulation', 'report_generation', 'finished'}
            has_required_statuses = required_statuses.issubset(set(statuses))
            self.results['workflow_validation_valid_stream_format'] = (has_required_statuses, events)
            print(f"    Stream format: {'✅ PASS' if has_required_statuses else '❌ FAIL'}")
        else:
            self.results['workflow_validation_valid_stream_format'] = (False, result_valid[1])
            print(f"    Stream format: ❌ FAIL - Base test failed")
        
        # Invalid workflow test (missing nodes)
        print("  - Invalid workflow test (missing nodes)...")
        invalid_workflow = {
            "workflow": {
                "name": "Test Workflow",
                # Missing nodes property
                "edges": [
                    {"from": "start", "to": "process"},
                    {"from": "process", "to": "end"}
                ]
            },
            "rules": []
        }
        result_invalid_nodes = self.test_stream_request('/workflow-validation/validate', invalid_workflow)
        self.results['workflow_validation_invalid_nodes'] = result_invalid_nodes
        print(f"    Invalid nodes: {'✅ PASS' if result_invalid_nodes[0] else '❌ FAIL'}")
        
        # Invalid workflow test (missing edges)
        print("  - Invalid workflow test (missing edges)...")
        invalid_workflow_edges = {
            "workflow": {
                "name": "Test Workflow",
                "nodes": {
                    "start": {"type": "start"},
                    "process": {"type": "task"},
                    "end": {"type": "end"}
                }
                # Missing edges property
            },
            "rules": []
        }
        result_invalid_edges = self.test_stream_request('/workflow-validation/validate', invalid_workflow_edges)
        self.results['workflow_validation_invalid_edges'] = result_invalid_edges
        print(f"    Invalid edges: {'✅ PASS' if result_invalid_edges[0] else '❌ FAIL'}")
        
        # Workflow with custom validation rules
        print("  - Workflow with custom validation rules...")
        rule_workflow = {
            "workflow": {
                "name": "Rule Test Workflow",
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
            "rules": [
                {
                    "type": "required_nodes",
                    "nodes": ["start", "process", "end"]
                }
            ]
        }
        result_rules = self.test_stream_request('/workflow-validation/validate', rule_workflow)
        self.results['workflow_validation_with_rules'] = result_rules
        print(f"    With custom rules: {'✅ PASS' if result_rules[0] else '❌ FAIL'}")

    def print_summary(self):
        """Print a summary of test results."""
        passed = sum(1 for success, _ in self.results.values() if success)
        total = len(self.results)
        
        print(f"\n📊 Test Summary: {passed}/{total} tests passed")
        
        if passed == total:
            print("🎉 All core features are functioning properly!")
        else:
            print(f"⚠️  {total - passed} tests need attention")
        
        print("\n📋 Detailed Results:")
        for test_name, (success, data) in self.results.items():
            status = "✅ PASS" if success else "❌ FAIL"
            print(f"  {test_name}: {status}")
            
            if not success:
                print(f"    Error: {data}")


def main():
    tester = CoreFeaturesTester()
    results = tester.run_comprehensive_tests()
    return results


if __name__ == "__main__":
    main()