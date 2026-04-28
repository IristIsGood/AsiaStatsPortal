<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait FormatsApiResponse
{
    protected function formatResponse(Request $request, $data, string $rootElement = 'data')
    {
        $format = strtolower($request->query('format', 'json'));

        return match($format) {
            'csv'   => $this->toCsv($data),
            'xml'   => $this->toXml($data, $rootElement),
            default => response()->json($data),
        };
    }

    // ── CSV ──────────────────────────────────────────
    private function toCsv($data): \Illuminate\Http\Response
    {
        $collection = collect($data);

        if ($collection->isEmpty()) {
            return response('', 204);
        }

        $headers = array_keys($collection->first()->toArray());
        $rows[]  = implode(',', $headers);

        foreach ($collection as $item) {
            $rows[] = implode(',', array_map(
                fn($v) => '"' . str_replace('"', '""', $v ?? '') . '"',
                $item->toArray()
            ));
        }

        return response(implode("\n", $rows), 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ]);
    }

    // ── XML ──────────────────────────────────────────
    private function toXml($data, string $rootElement): \Illuminate\Http\Response
    {
        $xml = new \SimpleXMLElement("<{$rootElement}/>");

        foreach (collect($data) as $item) {
            $node = $xml->addChild('item');
            foreach ($item->toArray() as $key => $value) {
                $node->addChild($key, htmlspecialchars((string)($value ?? '')));
            }
        }

        return response($xml->asXML(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}