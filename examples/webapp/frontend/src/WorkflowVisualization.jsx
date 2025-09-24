import React, { useState, useEffect, useRef } from 'react';

const WorkflowVisualization = ({ workflowData }) => {
  const svgRef = useRef(null);
  const [dimensions, setDimensions] = useState({ width: 800, height: 600 });

  // Update dimensions when the component mounts and on window resize
  useEffect(() => {
    const updateDimensions = () => {
      if (svgRef.current) {
        const width = svgRef.current.parentElement.clientWidth;
        const height = Math.max(400, window.innerHeight - 300);
        setDimensions({ width, height });
      }
    };

    updateDimensions();
    window.addEventListener('resize', updateDimensions);
    return () => window.removeEventListener('resize', updateDimensions);
  }, []);

  // If no workflow data, show a message
  if (!workflowData || !workflowData.nodes) {
    return (
      <div className="flex items-center justify-center h-full bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <div className="text-center p-8">
          <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <h3 className="mt-2 text-sm font-medium text-gray-900">No workflow data</h3>
          <p className="mt-1 text-sm text-gray-500">Provide workflow definition to visualize the graph</p>
        </div>
      </div>
    );
  }

  // Calculate node positions
  const nodes = Object.entries(workflowData.nodes).map(([id, node], index) => {
    const nodeCount = Object.keys(workflowData.nodes).length;
    const row = Math.floor(index / 3);
    const col = index % 3;
    
    return {
      id,
      ...node,
      x: (dimensions.width / 4) * (col + 1),
      y: (dimensions.height / (Math.ceil(nodeCount / 3) + 1)) * (row + 1)
    };
  });

  // Get node type color
  const getNodeTypeColor = (type) => {
    switch (type) {
      case 'start': return '#10B981'; // green
      case 'end': return '#EF4444'; // red
      case 'decision': return '#F59E0B'; // yellow
      case 'task': return '#3B82F6'; // blue
      default: return '#6B7280'; // gray
    }
  };

  // Get node type icon
  const getNodeTypeIcon = (type) => {
    switch (type) {
      case 'start': 
        return (
          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
      case 'end': 
        return (
          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        );
      case 'decision': 
        return (
          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
          </svg>
        );
      case 'task': 
        return (
          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        );
      default: 
        return (
          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
        );
    }
  };

  return (
    <div className="bg-white rounded-lg shadow-lg overflow-hidden">
      <div className="bg-indigo-600 text-white p-4">
        <h2 className="text-xl font-semibold">Workflow Visualization</h2>
      </div>
      <div className="p-4 overflow-auto">
        <svg 
          ref={svgRef}
          width={dimensions.width}
          height={dimensions.height}
          className="border border-gray-200 rounded-lg bg-gray-50"
        >
          {/* Render edges */}
          {workflowData.edges && workflowData.edges.map((edge, index) => {
            const fromNode = nodes.find(node => node.id === edge.from);
            const toNode = nodes.find(node => node.id === edge.to);
            
            if (!fromNode || !toNode) return null;
            
            return (
              <g key={index}>
                <line
                  x1={fromNode.x}
                  y1={fromNode.y}
                  x2={toNode.x}
                  y2={toNode.y}
                  stroke="#9CA3AF"
                  strokeWidth="2"
                  markerEnd="url(#arrowhead)"
                />
                <text
                  x={(fromNode.x + toNode.x) / 2}
                  y={(fromNode.y + toNode.y) / 2 - 10}
                  textAnchor="middle"
                  fill="#6B7280"
                  fontSize="12"
                  fontWeight="500"
                >
                  {edge.label || ''}
                </text>
              </g>
            );
          })}
          
          {/* Define arrowhead marker */}
          <defs>
            <marker
              id="arrowhead"
              markerWidth="10"
              markerHeight="7"
              refX="9"
              refY="3.5"
              orient="auto"
            >
              <polygon points="0 0, 10 3.5, 0 7" fill="#9CA3AF" />
            </marker>
          </defs>
          
          {/* Render nodes */}
          {nodes.map((node) => (
            <g key={node.id} transform={`translate(${node.x}, ${node.y})`}>
              <circle
                r="24"
                fill={getNodeTypeColor(node.type)}
                stroke="#FFFFFF"
                strokeWidth="2"
              />
              <circle
                r="28"
                fill="none"
                stroke={getNodeTypeColor(node.type)}
                strokeWidth="2"
                opacity="0.3"
              />
              <g transform="translate(-8, -8)">
                {getNodeTypeIcon(node.type)}
              </g>
              <text
                y="40"
                textAnchor="middle"
                fill="#4B5563"
                fontSize="12"
                fontWeight="500"
              >
                {node.id}
              </text>
            </g>
          ))}
        </svg>
        
        {/* Legend */}
        <div className="mt-4 flex flex-wrap gap-4">
          <div className="flex items-center">
            <div className="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
            <span className="text-sm text-gray-600">Start Node</span>
          </div>
          <div className="flex items-center">
            <div className="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
            <span className="text-sm text-gray-600">End Node</span>
          </div>
          <div className="flex items-center">
            <div className="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
            <span className="text-sm text-gray-600">Decision Node</span>
          </div>
          <div className="flex items-center">
            <div className="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
            <span className="text-sm text-gray-600">Task Node</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default WorkflowVisualization;