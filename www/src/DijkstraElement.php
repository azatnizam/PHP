<?php

namespace Tirei01\Hw15;

class DijkstraElement
{
    private Vertex $vertex;
    private ?int $lengthPath;
    private bool $isFinish;
    private ?Vertex $backVertex;
    public function __construct(Vertex $vertex, $isFinish = false, ?int $lengthPath = null, ?Vertex $backVertex = null)
    {
        $this->vertex = $vertex;
        $this->isFinish = $isFinish;
        $this->lengthPath = $lengthPath;
        $this->backVertex = $backVertex;
    }

    public function getVertex(){
        return $this->vertex;
    }

    /**
     * @return int|null
     */
    public function getLengthPath(): ?int
    {
        return $this->lengthPath;
    }

    /**
     * @return bool
     */
    public function isFinish(): bool
    {
        return $this->isFinish;
    }

    /**
     * @return Vertex|null
     */
    public function getBackVertex(): ?Vertex
    {
        return $this->backVertex;
    }

}